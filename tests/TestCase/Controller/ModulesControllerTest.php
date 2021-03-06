<?php

/**
 * BEdita, API-first content management framework
 * Copyright 2018 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

namespace App\Test\TestCase\Controller;

use App\Controller\Component\SchemaComponent;
use App\Controller\ModulesController;
use Aura\Intl\Exception;
use BEdita\SDK\BEditaClient;
use BEdita\SDK\BEditaClientException;
use BEdita\WebTools\ApiClientProvider;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * Sample controller wrapper, to add useful methods for test
 */
class ModulesControllerSample extends ModulesController
{
    /**
     * Getter for objectType protected var
     *
     * @return string
     */
    public function getObjectType() : string
    {
        return $this->objectType;
    }

    /**
     * Public version of parent function (protected) descendants
     *
     * @return void
     */
    public function descendants() : array
    {
        return parent::descendants();
    }

    /**
     * Update media urls, public for testing
     *
     * @param array $response The response
     * @return void
     */
    public function updateMediaUrls(array &$response) : void
    {
        parent::updateMediaUrls($response);
    }

    /**
     * Create new object from ajax request.
     *
     * @return void
     */
    public function getApiClient() : BEditaClient
    {
        return $this->apiClient;
    }

    /**
     * Create new object from ajax request.
     *
     * @return void
     */
    public function saveJson() : void
    {
        parent::saveJson();
    }
}

/**
 * {@see \App\Controller\ModulesController} Test Case
 *
 * @coversDefaultClass \App\Controller\ModulesController
 */
class ModulesControllerTest extends TestCase
{
    /**
     * Test Modules controller
     *
     * @var App\Test\TestCase\Controller\ModulesControllerSample
     */
    public $controller;

    /**
     * Test api client
     *
     * @var BEdita\SDK\BEditaClient
     */
    public $client;

    /**
     * Uname for test object
     *
     * @var string
     */
    protected $uname = 'modules-controller-test-document';

    /**
     * Test request config
     *
     * @var array
     */
    public $defaultRequestConfig = [
        'environment' => [
            'REQUEST_METHOD' => 'GET',
        ],
        'get' => [],
        'params' => [
            'object_type' => 'documents',
        ],
    ];

    /**
     * Setup api client and auth
     *
     * @return void
     */
    private function setupApi() : void
    {
        $this->client = ApiClientProvider::getApiClient();
        $adminUser = getenv('BEDITA_ADMIN_USR');
        $adminPassword = getenv('BEDITA_ADMIN_PWD');
        $response = $this->client->authenticate($adminUser, $adminPassword);
        $this->client->setupTokens($response['meta']);
    }

    /**
     * Setup controller to test with request config
     *
     * @param array|null $requestConfig
     * @return void
     */
    protected function setupController(? array $requestConfig = []) : void
    {
        $config = array_merge($this->defaultRequestConfig, $requestConfig);
        $request = new ServerRequest($config);
        $this->controller = new ModulesControllerSample($request);
        // force modules load
        $this->controller->Auth->setUser(['id' => 'dummy']);
        $this->controller->Modules->startup();
        $this->setupApi();
        $this->createTestObject();
    }

    /**
     * Test `initialize` method
     *
     * @covers ::initialize()
     *
     * @return void
     */
    public function testInitialize() : void
    {
        // Setup controller for test
        $this->setupController(); // it already calls initialize, internally
        static::assertEquals('documents', $this->controller->getObjectType());
        static::assertEquals('documents', $this->controller->Modules->getConfig('currentModuleName'));
        static::assertEquals('documents', $this->controller->Schema->getConfig('type'));
    }

    /**
     * Test `index` method
     *
     * @covers ::index()
     *
     * @return void
     */
    public function testIndex() : void
    {
        // Setup controller for test
        $this->setupController();

        // do controller call
        $result = $this->controller->index();

        // verify response status code and type
        static::assertNull($result);
        static::assertEquals(200, $this->controller->response->statusCode());
        static::assertEquals('text/html', $this->controller->response->type());

        // verify expected vars in view
        $this->assertExpectedViewVars(['objects', 'meta', 'links', 'types', 'properties']);
    }

    /**
     * Test `descendants` method on concrete type
     *
     * @covers ::descendants()
     *
     * @return void
     */
    public function testEmptyDescendants() : void
    {
        // Setup controller for test / on documents
        $this->setupController();

        // do controller call
        $result = $this->controller->descendants();

        // verify response status code and type
        static::assertEmpty($result);
        static::assertEquals(200, $this->controller->response->statusCode());
        static::assertEquals('text/html', $this->controller->response->type());
    }

    /**
     * Test `descendants` method on abstract type
     *
     * @covers ::descendants()
     *
     * @return void
     */
    public function testDescendants() : void
    {
        // Setup controller with `objects` as type
        $this->setupController([
            'params' => [
                'object_type' => 'objects',
            ],
        ]);

        $result = $this->controller->descendants();

        // verify response status code and type
        static::assertNotEmpty($result);
        static::assertEquals(200, $this->controller->response->statusCode());
    }

    /**
     * Test `descendants` method on missing type
     *
     * @covers ::descendants()
     *
     * @return void
     */
    public function testWrongDescendants() : void
    {
        // Setup controller with `objects` as type
        $this->setupController([
            'params' => [
                'object_type' => 'gustavos',
            ],
        ]);

        $result = $this->controller->descendants();

        // verify response status code and type
        static::assertEmpty($result);
        static::assertEquals(200, $this->controller->response->statusCode());
    }
    /**
     * Test `view` method
     *
     * @covers ::view()
     *
     * @return void
     */
    public function testView() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object ID for test
        $id = $this->getTestId();

        // do controller call
        $result = $this->controller->view($id);

        // verify response status code and type
        static::assertNull($result);
        static::assertEquals(200, $this->controller->response->statusCode());
        static::assertEquals('text/html', $this->controller->response->type());

        // verify expected vars in view
        $this->assertExpectedViewVars(['object', 'included', 'schema', 'properties', 'relations']);
    }

    /**
     * Test `uname` method
     *
     * @covers ::uname()
     *
     * @return void
     */
    public function testUname() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object ID for test
        $id = $this->getTestId();

        // do controller call
        $result = $this->controller->uname($id);

        // verify response status code and type
        $header = $result->header();
        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());
        static::assertEquals(sprintf('/%s/view/%s', $this->controller->getObjectType(), $id), $header['Location']);
    }

    /**
     * Test `uname` method, case 404 Not Found
     *
     * @covers ::uname()
     *
     * @return void
     */
    public function testUname404() : void
    {
        // Setup controller for test
        $exception = new BEditaClientException('Not Found', 404);
        $this->setupController();

        // do controller call
        $result = $this->controller->uname('just-a-wrong-uname');

        // verify response status code and type
        $header = $result->header();
        static::assertEquals(302, $result->statusCode()); // redir to referer
        static::assertEquals('/', $header['Location']);
    }

    /**
     * Test `create` method
     *
     * @covers ::create()
     *
     * @return void
     */
    public function testCreate() : void
    {
        // Setup controller for test
        $this->setupController();

        // do controller call
        $this->controller->create();

        // verify expected vars in view
        $this->assertExpectedViewVars(['object', 'schema', 'properties']);
    }

    /**
     * Test `create` method
     *
     * @covers ::create()
     *
     * @return void
     */
    public function testCreate302() : void
    {
        // Setup controller for test
        $this->setupController([
            'environment' => [
                'REQUEST_METHOD' => 'GET',
            ],
            'get' => [],
            'params' => [
                'object_type' => 'objects',
            ],
        ]);

        // do controller call
        $result = $this->controller->create();

        // verify response status code and type
        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());
    }

    /**
     * Test `save` method
     *
     * @covers ::save()
     *
     * @return void
     */
    public function testSave() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object for test
        $o = $this->getTestObject();
        $config = [
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
            'post' => [
                'id' => $o['id'],
                'title' => $o['attributes']['title'],
            ],
            'params' => [
                'object_type' => 'documents',
            ],
        ];
        $request = new ServerRequest($config);
        $this->controller = new ModulesControllerSample($request);

        // do controller call
        $result = $this->controller->save();

        // verify response status code and type
        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());
    }

    /**
     *
     * Data provider for `testSaveJson` test case.
     *
     */
    public function saveJsonProvider()
    {
        return [
            'save' => [
                [
                    'code' => 200,
                    'message' => 'OK'
                ],
                [
                    'params' => [
                        'object_type' => 'images'
                    ],
                    'body' => [
                        'title' => 'bibo',
                    ]
                ],
            ],
            'exception' => [
                [
                    'code' => 404,
                    'message' => 'Not Found',
                ],
                [
                    'params' => [
                        'object_type' => 'drago',
                    ],
                    'body' => [
                        'title' => 'bibo',
                    ]
                ]
            ]
        ];
    }

    /**
     * Test `saveJson` method
     *
     * @dataProvider saveJsonProvider()
     * @covers ::saveJson()
     *
     * @return void
     */
    public function testSaveJson($expected, $data) : void
    {
        // Setup controller for test
        $this->setupController();

        $config = [
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
            'post' => $data['body'],
            'params' => $data['params'],
        ];

        $request = new ServerRequest($config);
        $this->controller = new ModulesControllerSample($request);

        // do controller call
        $this->controller->saveJson();

        // verify response status code and type
        $result = $this->controller->getApiClient();
        static::assertEquals($expected['code'], $result->getStatusCode());
        static::assertEquals($expected['message'], $result->getStatusMessage());
    }

    /**
     * Test `delete` method
     *
     * @covers ::delete()
     *
     * @return void
     */
    public function testDelete() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object for test
        $o = $this->getTestObject();
        $config = [
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
            'post' => [
                'id' => $o['id'],
            ],
            'params' => [
                'object_type' => 'documents',
            ],
        ];
        $request = new ServerRequest($config);
        $this->controller = new ModulesControllerSample($request);

        // do controller call
        $result = $this->controller->delete();

        // verify response status code and type
        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());

        // restore test object
        $this->restoreTestObject($o['id'], 'documents');
    }

    /**
     * Test `relatedJson` method
     *
     * @covers ::relatedJson()
     *
     * @return void
     */
    public function testRelatedJson() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object ID for test
        $id = $this->getTestId();

        // do controller call
        $this->controller->relatedJson($id, 'translations');

        // verify expected vars in view
        $this->assertExpectedViewVars(['_serialize', 'data']);
    }

    /**
     * Test `updateMediaUrls` method
     *
     * @covers ::updateMediaUrls()
     *
     * @return void
     */
    public function testUpdateMediaUrls() : void
    {
        // Setup controller for test
        $this->setupController();

        // do controller call
        $response = [
            'data' => [
                [
                    'id' => 99911,
                    'type' => 'images',
                    'attributes' => [
                        'provider_thumbnail' => 'https://thumb/99911',
                    ],
                ],
                [
                    'id' => 99922,
                    'type' => 'images',
                    'relationships' => [
                        'streams' => [
                            'data' => [
                                [
                                    'id' => '99922999999999',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'included' => [
                '9991' => [
                    'id' => '9991',
                    'type' => 'documents',
                    'attributes' => [
                        'title' => 'first doc',
                    ],
                ],
                '9992' => [
                    'id' => '9992',
                    'type' => 'documents',
                    'attributes' => [
                        'title' => 'second doc',
                    ],
                ],
                '99922999999999' => [
                    'id' => '99922999999999',
                    'type' => 'streams',
                    'meta' => [
                        'url' => 'https://thumb/99922',
                    ]
                ],
            ],
        ];
        $this->controller->updateMediaUrls($response);
        foreach ($response['data'] as $item) {
            static::assertNotEmpty($item['meta']['url']);
            static::assertEquals(sprintf('https://thumb/%s', $item['id']), $item['meta']['url']);
        }
    }

    /**
     * Test `updateMediaUrls` method
     *
     * @covers ::updateMediaUrls()
     *
     * @return void
     */
    public function testUpdateMediaUrlsWithEmptyData() : void
    {
        // Setup controller for test
        $this->setupController();

        // do controller call
        $response = [];
        $this->controller->updateMediaUrls($response);

        static::assertEmpty($response);
    }

    /**
     * Test `relationData` method
     *
     * @covers ::relationData()
     *
     * @return void
     */
    public function testRelationData() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object ID for test
        $id = $this->getTestId();

        // do controller call
        $this->controller->relationData($id, 'translations');

        // verify expected vars in view
        $this->assertExpectedViewVars(['_serialize']);
    }

    /**
     * Data provider for `testRelationshipsJson` test case.
     *
     * @return array
     */
    public function relationshipsJsonProvider() : array
    {
        return [
            'children' => [
                'children',
                'objects',
            ],
            'parent' => [
                'parent',
                'folders',
            ],
            'parents' => [
                'parents',
                'folders',
            ],
            'document' => [
                'translations',
                'documents',
            ],
        ];
    }

    /**
     * Test `relationshipsJson` method
     *
     * @param string $relation The relation to test
     * @param string $objectType The object type / endpoint
     * @param array $expected The expected data
     *
     * @covers ::relationshipsJson()
     * @dataProvider relationshipsJsonProvider()
     * @return void
     */
    public function testRelationshipsJson(string $relation, string $objectType) : void
    {
        // Setup controller for test
        $this->setupController([
            'environment' => [
                'REQUEST_METHOD' => 'GET',
            ],
            'get' => [],
            'params' => [
                'object_type' => $objectType,
            ],
        ]);

        // get object ID for test
        $id = $this->getTestId();

        // do controller call
        $this->controller->relationshipsJson($id, $relation);

        // verify expected vars in view
        $this->assertExpectedViewVars(['_serialize']);
    }

    /**
     * Data provider for `testGetThumbsUrls` test case.
     *
     * @return array
     */
    public function getThumbsUrlsProvider() : Array
    {
        return [
            // test with empty object
            'emptyResponse' => [
                [],
                [],
            ],
            // test with objct without ids
            'responseWithoutIds' => [
                ['data' => []],
                ['data' => []],
            ],
            // test with objct without ids
            'responseWithoutIds' => [
                ['data' => [
                    'ids' => [],
                ]],
                ['data' => [
                    'ids' => [],
                ]],
            ],
            // correct result
            'correctResponseMock' => [
                [ // expected
                    'data' => [
                        [
                            'id' => '43',
                            'type' => 'images',
                            'meta' =>
                                [
                                    'url' => 'https://media.example.com/be4-media-test/test-thumbs/thumb1.png',
                                ],
                        ],
                        [
                            'id' => '45',
                            'type' => 'images',
                            'meta' =>
                                [
                                    'url' => 'https://media.example.com/be4-media-test/test-thumbs/thumb2.png',
                                ],
                        ],
                    ],
                ],
                [ // data
                    'data' => [
                        [
                            'id' => '43',
                            'type' => 'images',
                            'meta' => [],
                        ],
                        [
                            'id' => '45',
                            'type' => 'images',
                            'meta' => [],
                        ],
                    ],
                ],
                [ // mock response for api
                    'meta' => [
                        'thumbnails' => [
                            [
                                'url' => 'https://media.example.com/be4-media-test/test-thumbs/thumb1.png',
                                'id' => 43,
                            ],
                            [
                                'url' => 'https://media.example.com/be4-media-test/test-thumbs/thumb2.png',
                                'id' => 45,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test `getThumbsUrls` method
     *
     * @dataProvider getThumbsUrlsProvider()
     * @covers ::getThumbsUrls()
     *
     * @return void
     */
    public function testGetThumbsUrls($expected, $data, $mockResponse = null) : void
    {
        $this->setupController();

        if (!empty($mockResponse)) {
            $expectedException = new BEditaClientException('error');

            $apiClient = $this->getMockBuilder(BEditaClient::class)
                ->setConstructorArgs(['https://media.example.com'])
                ->getMock();

            $apiClient->method('get')
                ->with('/media/thumbs?ids=43,45&options[w]=400')
                ->willReturn($mockResponse);

            $this->controller->apiClient = $apiClient;
        }

        $this->controller->getThumbsUrls($data);
        static::assertEquals($expected, $data);
    }

    /**
     * Test `bulkActions` method
     *
     * @covers ::bulkActions()
     *
     * @return void
     */
    public function testBulkActions() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object for test
        $o = $this->getTestObject();

        // Setup again for test
        $this->setupController([
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
            'post' => [
                'ids' => $o['id'],
                'attributes' => [
                    'status' => $o['attributes']['status'],
                ],
            ],
            'params' => [
                'object_type' => 'documents',
            ],
        ]);

        // do controller call
        $result = $this->controller->bulkActions();

        // verify response status code and type
        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());
    }

    /**
     * Test `bulkActions` method with errors
     *
     * @covers ::bulkActions()
     *
     * @return void
     */
    public function testBulkActionsWithErrors() : void
    {
        // Setup controller for test
        $this->setupController();

        // get object for test
        $o = $this->getTestObject();

        // Setup again for test
        $this->setupController([
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
            'post' => [
                'ids' => $o['id'],
                'attributes' => [
                    'status' => $o['attributes']['status'],
                ],
            ],
            'params' => [
                'object_type' => $o['type'],
            ],
        ]);

        // Setup mock API client.
        $apiClient = $this->getMockBuilder(BEditaClient::class)
            ->setConstructorArgs(['https://media.example.org'])
            ->getMock();

        $requestBody = [
            'id' => $o['id'],
            'status' => $o['attributes']['status'],
        ];

        $exception = new BEditaClientException([
            'id' => $o['id'],
            'message' => 'Not Found',
        ], 404);

        $apiClient->method('save')
            ->with($o['type'], $requestBody)
            ->willThrowException($exception);

        $this->controller->apiClient = $apiClient;

        // do controller call
        $result = $this->controller->bulkActions();

        $flash = $this->controller->request->session()->read('Flash.flash');
        $message = $flash[0]['message'];
        $expected = 'Bulk Action failed on: ';
        // verify response status code and type

        static::assertEquals(302, $result->statusCode());
        static::assertEquals('text/html', $result->type());
        static::assertEquals($expected, $message);
    }

    /**
     * Test `getSchemaForIndex` method with errors
     *
     * @covers ::getSchemaForIndex()
     *
     * @return void
     */
    public function testGetSchemaForIndex() : void
    {
        $type = 'documents';
        $mockResponse = [
            'properties' => [
                'enum_prop' => [
                    'type' => 'string',
                    'enum' => [
                        'enum1',
                        'enum2',
                        'enum3',
                    ],
                ]
            ]
        ];
        $expected = [
            'properties' => [
                'enum_prop' => [
                    'type' => 'string',
                    'enum' => [
                        '',
                        'enum1',
                        'enum2',
                        'enum3',
                    ],
                ]
            ]
        ];

        $this->setupController();
        $this->controller->Schema = $this->createMock(SchemaComponent::class);
        $this->controller->Schema->method('getSchema')
            ->with($type)
            ->willReturn($mockResponse);

        $actual = $this->controller->getSchemaForIndex($type);

        static::assertEquals($expected, $actual);
    }

    /**
     * Get test object id
     *
     * @return void
     */
    private function getTestId()
    {
        // call index and get first available object, for test view
        $o = $this->getTestObject();

        return $o['id'];
    }

    /**
     * Get an object for test purposes
     *
     * @return array
     */
    private function getTestObject()
    {
        $response = $this->client->getObjects('documents', ['filter' => ['uname' => $this->uname]]);

        if (!empty($response['data'][0])) {
            return $response['data'][0];
        }

        return null;
    }

    /**
     * Create a object for test purposes (if not available already)
     *
     * @return array
     */
    private function createTestObject()
    {
        $o = $this->getTestObject();
        if ($o == null) {
            $response = $this->client->save('documents', [
                'title' => 'modules controller test document',
                'uname' => $this->uname
            ]);
            $o = $response['data'];
        }

        return $o;
    }

    /**
     * Restore object by id
     *
     * @param string|int $id The object ID
     * @param string $type The object type
     * @return void
     */
    private function restoreTestObject($id, $type)
    {
        $o = $this->getTestObject();
        if ($o == null) {
            $response = $this->client->restoreObject($id, $type);
        }
    }

    /**
     * Verify existence of vars in controller view
     *
     * @param array $expected The expected vars in view
     * @return void
     */
    private function assertExpectedViewVars($expected)
    {
        foreach ($expected as $varName) {
            static::assertArrayHasKey($varName, $this->controller->viewVars);
        }
    }
}
