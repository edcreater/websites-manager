<?php
/**
 * Created by PhpStorm.
 * User: georgy
 * Date: 18.10.14
 * Time: 2:03
 */

namespace frontend\controllers;

use common\models\Item;
use common\models\Server;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use DOMDocument;

class ServerController extends Controller {


	public function actionIndex(): string {
		$query   = Server::find();
		$servers = new ActiveDataProvider(
			array(
				'query'      => $query,
				'pagination' => array(
					'pageSize' => 20,
				),
				'sort'       => array(
					'defaultOrder' => array(
						'title' => SORT_ASC,
					),
				),
			)
		);

		return $this->render(
			'index',
			array(
				'servers' => $servers,
			)
		);
	}

	public function actionView( int $id ): string {
		$server = Server::findById( $id );

		$filter_https = Yii::$app->request->get( 'http' );

		if ( $filter_https == 'on' ) {
			$items = new ActiveDataProvider(
				array(
					'query' => Item::find()
						->where( array( 'protocol' => 'http' ) )
						->andWhere( array( 'server_id' => $server->id ) )
						->orderBy( array( 'publish_date' => SORT_DESC ) ),
				)
			);

			$items->setPagination(
				array(
					'pageSize' => Yii::$app->params['pageSize'],
				)
			);

			return $this->render(
				'view',
				array(
					'server'  => $server,
					'items'   => $items,
					'servers' => Server::findServers(),
				)
			);
		}

		$items = $server->getItems();
		$items->setPagination(
			array(
				'pageSize' => Yii::$app->params['pageSize'],
			)
		);

		return $this->render(
			'view',
			array(
				'server'  => $server,
				'items'   => $items,
				'servers' => Server::findServers(),
			)
		);
	}

	public function actionCheckDiff( int $id ): string {
		$result       = '';
		$server       = Server::findById( $id, true );
		$domainsQuery = $server->getItems();
		$domains      = $domainsQuery->query->all();

		$api_url = 'https://' . $server->ip . ':1500/ispmgr?authinfo=root:' . $server->password . '&out=xml&func=webdomain';

		$curlInit = curl_init( $api_url );
		curl_setopt( $curlInit, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $curlInit, CURLOPT_HEADER, true );
		curl_setopt( $curlInit, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curlInit, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $curlInit, CURLOPT_SSL_VERIFYPEER, 0 );

		// get answer
		$response = curl_exec( $curlInit );

		if ( curl_errno( $curlInit ) ) {
			var_dump( curl_errno( $curlInit ) );
			die;
		}

		$curl_info   = curl_getinfo( $curlInit );
		$header_size = $curl_info['header_size'];
		$header      = substr( $response, 0, $header_size );
		$body        = substr( $response, $header_size );

		// После этого переменная $result содержит XML-документ со списком WWW-доменов,
		// либо сообщение об ошибке

		$isp_domains   = array();
		$admin_domains = array();

		$doc = new DOMDocument();
		if ( $doc->loadXML( $body ) ) {
			$root = $doc->documentElement;
			foreach ( $root->childNodes as $elem ) {
				foreach ( $elem->childNodes as $node ) {
					if ( $node->nodeType !== 1 ) { // Element nodes are of nodeType 1. Text 3. Comments 8. etc rtm
						continue;
					}
					if ( $node->tagName == 'name' ) {
						$isp_domains[] = $node->nodeValue;
					}
				}
			}
		}

		foreach ( $domains as $domain ) {
			$admin_domains[] = $domain->domain;
		}

		return $this->render(
			'checkDiff',
			array(
				'model'      => $server,
				'diff_isp'   => array_diff( $isp_domains, $admin_domains ),
				'diff_admin' => array_diff( $admin_domains, $isp_domains ),
			)
		);
	}

	public function actionCheckOnline( int $id ): string {
		ini_set( 'max_execution_time', 1800 ); // 300 seconds = 5 minutes

		$checked      = array();
		$server       = Server::findById( $id, true );
		$domainsQuery = $server->getItems();
		$domains      = $domainsQuery->query->all();

		foreach ( $domains as $domain ) {
			$host = $domain->protocol . '://' . $domain->domain;

			$checked[ $host ] = $this->checkOnline( $host );
		}

		return $this->render(
			'checkOnline',
			array(
				'model'   => $server,
				'checked' => $checked,
			)
		);
	}

	private function checkOnline( $domain ) {
		$curlInit = curl_init( $domain );
		curl_setopt( $curlInit, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $curlInit, CURLOPT_HEADER, true );
		curl_setopt( $curlInit, CURLOPT_NOBODY, true );
		curl_setopt( $curlInit, CURLOPT_RETURNTRANSFER, true );

		// get answer
		$response = curl_exec( $curlInit );

		if ( ! curl_errno( $curlInit ) ) {
			$info = curl_getinfo( $curlInit );
		}

		curl_close( $curlInit );

		if ( $response ) {
			return $info['http_code'];
		}
		return '0';
	}
}
