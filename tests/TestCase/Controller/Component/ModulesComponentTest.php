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

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ModulesComponent;
use App\Core\Exception\UploadException;
use BEdita\SDK\BEditaClient;
use BEdita\SDK\BEditaClientException;
use BEdita\WebTools\ApiClientProvider;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;

/**
 * {@see \App\Controller\Component\ModulesComponent} Test Case
 *
 * @coversDefaultClass \App\Controller\Component\ModulesComponent
 */
class ModulesComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\ModulesComponent
     */
    public $Modules;

    /**
     * Test api client
     *
     * @var BEdita\SDK\BEditaClient
     */
    public $client;

    /**
     * {@inheritDoc}
     */
    public function setUp() : void
    {
        parent::setUp();

        $controller = new Controller();
        $registry = $controller->components();
        $registry->load('Auth');
        $this->Modules = $registry->load(ModulesComponent::class);
        $this->Auth = $registry->load(AuthComponent::class);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown() : void
    {
        unset($this->Modules);

        // reset client, force new client creation
        ApiClientProvider::setApiClient(null);
        parent::tearDown();
    }

    /**
     * Data provider for `testGetProject` test case.
     *
     * @return array
     */
    public function getProjectProvider()
    {
        return [
            'ok' => [
                [
                    'name' => 'BEdita',
                    'version' => 'v4.0.0-gustavo',
                    'colophon' => '',
                ],
                [
                    'project' => [
                        'name' => 'BEdita',
                    ],
                    'version' => 'v4.0.0-gustavo',
                ],
            ],
            'empty' => [
                [
                    'name' => '',
                    'version' => '',
                    'colophon' => '',
                ],
                [],
            ],
            'client exception' => [
                [
                    'name' => '',
                    'version' => '',
                    'colophon' => '',
                ],
                new BEditaClientException('I am a client exception'),
            ],
            'other exception' => [
                new \RuntimeException('I am some other kind of exception', 999),
                new \RuntimeException('I am some other kind of exception', 999),
            ],
        ];
    }

    /**
     * Test `getProject()` method.
     *
     * @param array|\Exception $expected Expected result.
     * @param array|\Exception $meta Response to `/home` endpoint.
     * @return void
     *
     * @dataProvider getProjectProvider()
     * @covers ::getMeta()
     * @covers ::getProject()
     */
    public function testGetProject($expected, $meta) : void
    {
        if ($expected instanceof \Exception) {
            $this->expectException(get_class($expected));
            $this->expectExceptionCode($expected->getCode());
            $this->expectExceptionMessage($expected->getMessage());
        }

        // Setup mock API client.
        $apiClient = $this->getMockBuilder(BEditaClient::class)
            ->setConstructorArgs(['https://api.example.org'])
            ->getMock();
        if ($meta instanceof \Exception) {
            $apiClient->method('get')
                ->with('/home')
                ->willThrowException($meta);
        } else {
            $apiClient->method('get')
                ->with('/home')
                ->willReturn(compact('meta'));
        }
        ApiClientProvider::setApiClient($apiClient);

        $actual = $this->Modules->getProject();

        static::assertEquals($expected, $actual);
    }

    /**
     * Data provider for `testIsAbstract` test case.
     *
     * @return array
     */
    public function isAbstractProvider() : array
    {
        return [
            'isAbstractTrue' => [
                true,
                'objects',
            ],
            'isAbstractFalse' => [
                false,
                'documents',
            ],
        ];
    }

    /**
     * Test `isAbstract()` method.
     *
     * @param boolean $expected expected results from test
     * @param string $data setup data for test, object type
     *
     * @dataProvider isAbstractProvider()
     * @covers ::isAbstract()
     *
     * @return void
     */
    public function testIsAbstract($expected, $data) : void
    {
        $userId = 1;
        $this->Auth->setUser(['id' => $userId]);
        $this->Modules->getController()->dispatchEvent('Controller.startup');
        $actual = $this->Modules->isAbstract($data);

        static::assertEquals($expected, $actual);
    }

    /**
     * Data provider for `testObjectTypes` test case.
     *
     * @return array
     */
    public function objectTypesProvider() : array
    {
        return [
            'empty' => [
                [],
                null,
            ],
            'abstractList' => [
                [
                    'objects',
                    'media',
                ],
                true,
            ],
            'concreteList' => [
                [
                    'folders',
                    'documents',
                    'events',
                    'news',
                    'locations',
                    'images',
                    'videos',
                    'audio',
                    'files',
                    'users',
                    'profiles',
                ],
                false,
            ],
        ];
    }

    /**
     * Test `objectTypes()` method.
     *
     * @param array $expected expected results from test
     * @param boolean|null $data setup data for test
     *
     * @dataProvider objectTypesProvider()
     * @covers ::objectTypes()
     *
     * @return void
     */
    public function testObjectTypes($expected, $data) : void
    {
        $userId = 1;
        $this->Auth->setUser(['id' => $userId]);
        if (!empty($expected)) {
            $this->Modules->getController()->dispatchEvent('Controller.startup');
        }
        $actual = $this->Modules->objectTypes($data);
        sort($actual);
        sort($expected);
        static::assertEquals($expected, $actual);
    }

    /**
     * Data provider for `testGetModules` test case.
     *
     * @return array
     */
    public function getModulesProvider() : array
    {
        return [
            'ok' => [
                [
                    'bedita',
                    'supporto',
                    'gustavo',
                    'trash',
                    'plugin',
                ],
                [
                    'resources' => [
                        [
                            'name' => 'gustavo',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'supporto',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'bedita',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'trash',
                        ],
                    ],
                ],
                [
                    'bedita',
                    'supporto',
                ],
                [
                    [
                        'name' => 'plugin',
                    ],
                ],
            ],
            'ok (trash first)' => [
                [
                    'trash',
                    'supporto',
                    'bedita',
                    'gustavo',
                ],
                [
                    'resources' => [
                        [
                            'name' => 'gustavo',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'supporto',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'bedita',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'trash',
                        ],
                    ],
                ],
                [
                    'trash',
                    'supporto',
                ],
            ],
            'client exception' => [
                [],
                new BEditaClientException('I am a client exception'),
            ],
            'other exception' => [
                new \RuntimeException('I am some other kind of exception', 999),
                new \RuntimeException('I am some other kind of exception', 999),
            ],
        ];
    }

    /**
     * Test `getModules()` method.
     *
     * @param string[]|\Exception $expected Expected result.
     * @param array|\Exception $meta Response to `/home` endpoint.
     * @param string[] $order Configured modules order.
     * @return void
     *
     * @dataProvider getModulesProvider()
     * @covers ::getMeta()
     * @covers ::getModules()
     */
    public function testGetModules($expected, $meta, array $order = [], array $plugins = []) : void
    {
        Configure::write('Modules.order', $order);
        Configure::write('Modules.plugins', $plugins);

        if ($expected instanceof \Exception) {
            $this->expectException(get_class($expected));
            $this->expectExceptionCode($expected->getCode());
            $this->expectExceptionMessage($expected->getMessage());
        }

        // Setup mock API client.
        $apiClient = $this->getMockBuilder(BEditaClient::class)
            ->setConstructorArgs(['https://api.example.org'])
            ->getMock();
        if ($meta instanceof \Exception) {
            $apiClient->method('get')
                ->with('/home')
                ->willThrowException($meta);
        } else {
            $apiClient->method('get')
                ->with('/home')
                ->willReturn(compact('meta'));
        }
        ApiClientProvider::setApiClient($apiClient);

        $actual = Hash::extract($this->Modules->getModules(), '{*}.name');

        static::assertSame($expected, $actual);
    }

    /**
     * Data provider for `testBeforeRender` test case.
     *
     * @return array
     */
    public function startupProvider() : array
    {
        return [
            'without current module' => [
                1,
                [
                    'gustavo',
                    'supporto',
                ],
                null,
                [
                    'name' => 'BEdita',
                    'version' => 'v4.0.0-gustavo',
                    'colophon' => '',
                ],
                [
                    'resources' => [
                        [
                            'name' => 'supporto',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'gustavo',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                    ],
                    'project' => [
                        'name' => 'BEdita',
                    ],
                    'version' => 'v4.0.0-gustavo',
                ],
                [
                    'gustavo',
                ],
            ],
            'with current module' => [
                1,
                [
                    'gustavo',
                    'supporto',
                ],
                'supporto',
                [
                    'name' => 'BEdita',
                    'version' => 'v4.0.0-gustavo',
                    'colophon' => '',
                ],
                [
                    'resources' => [
                        [
                            'name' => 'supporto',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                        [
                            'name' => 'gustavo',
                            'hints' => [
                                'object_type' => true,
                            ],
                        ],
                    ],
                    'project' => [
                        'name' => 'BEdita',
                    ],
                    'version' => 'v4.0.0-gustavo',
                ],
                [
                    'gustavo',
                ],
                'supporto',
            ],
            'no user' => [
                null,
                [],
                null,
                [],
                [],
                [],
                null,
            ],
        ];
    }

    /**
     * Test `startup()` method.
     *
     * @param int|null $userId User id.
     * @param string[] $modules Expected module names.
     * @param string|null $currentModule Expected current module name.
     * @param array $project Expected project info.
     * @param array $meta Response to `/home` endpoint.
     * @param string[] $order Configured modules order.
     * @param string|null $currentModuleName Current module.
     * @return void
     *
     * @dataProvider startupProvider()
     * @covers ::startup()
     */
    public function testBeforeRender($userId, $modules, ?string $currentModule, array $project, array $meta, array $order = [], ?string $currentModuleName = null) : void
    {
        Configure::write('Modules.order', $order);

        if ($userId) {
            $this->Auth->setUser(['id' => $userId]);
        }

        // Setup mock API client.
        $apiClient = $this->getMockBuilder(BEditaClient::class)
            ->setConstructorArgs(['https://api.example.org'])
            ->getMock();
        $apiClient->method('get')
            ->with('/home')
            ->willReturn(compact('meta'));

        ApiClientProvider::setApiClient($apiClient);

        $clearHomeCache = true;
        $this->Modules->setConfig(compact('apiClient', 'currentModuleName', 'clearHomeCache'));

        $controller = $this->Modules->getController();
        $controller->dispatchEvent('Controller.startup');

        $viewVars = $controller->viewVars;
        static::assertArrayHasKey('project', $viewVars);
        static::assertEquals($project, $viewVars['project']);
        static::assertArrayHasKey('modules', $viewVars);
        static::assertSame($modules, Hash::extract($viewVars['modules'], '{*}.name'));
        if ($currentModule !== null) {
            static::assertArrayHasKey('currentModule', $viewVars);
            static::assertSame($currentModule, Hash::get($viewVars['currentModule'], 'name'));
        } else {
            static::assertArrayNotHasKey('currentModule', $viewVars);
        }
    }

    /**
     * Data provider for `testUpload` test case.
     *
     * @return array
     */
    public function uploadProvider() : array
    {
        $name = 'test.png';
        $file = getcwd() . sprintf('/tests/files/%s', $name);
        $type = mime_content_type($file);
        $error = UPLOAD_ERR_OK;

        return [
            'no file' => [
                [
                    'file' => [],
                ],
                null,
                false,
            ],
            'file.name empty' => [
                [
                    'file' => ['a'] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.name'),
                false,
            ],
            'file.name not a string' => [
                [
                    'file' => ['name' => 12345] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.name'),
                false,
            ],
            'file.tmp_name (filepath) empty' => [
                [
                    'file' => ['name' => 'dummy.txt'] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.tmp_name'),
                false,
            ],
            'file.tmp_name (filepath) not a string' => [
                [
                    'file' => [
                        'name' => 'dummy.txt',
                        'tmp_name' => 12345,
                    ] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.tmp_name'),
                false,
            ],
            'file.type empty' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                    ] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.type'),
                false,
            ],
            'file.type not a string' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => 12345,
                    ] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: file.type'),
                false,
            ],
            'model-type empty' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => $type,
                    ] + compact('error'),
                ],
                new InternalErrorException('Invalid form data: model-type'),
                false,
            ],
            'model-type not a string' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => $type,
                    ] + compact('error'),
                    'model-type' => 12345,
                ],
                new InternalErrorException('Invalid form data: model-type'),
                false,
            ],
            'upload ok' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => $type,
                    ] + compact('error'),
                    'model-type' => 'images',
                ],
                null,
                true,
            ],
            'generic upload error' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => $type,
                        'error' => !UPLOAD_ERR_OK,
                    ],
                    'model-type' => 'images',
                ],
                new UploadException(null, !UPLOAD_ERR_OK),
                true,
            ],
            'save with empty file' => [
                [
                    'file' => [
                        'name' => $name,
                        'tmp_name' => $file,
                        'type' => $type,
                        'error' => UPLOAD_ERR_NO_FILE,
                    ],
                    'model-type' => 'images',
                ],
                null,
                false,
            ],
        ];
    }

    /**
     * Test `upload` method
     *
     * @param array $requestData The request data
     * @param Expection|null $expectedException The exception expected
     * @param boolean $uploaded The upload result
     * @return void
     *
     * @covers ::upload()
     * @covers ::removeStream()
     * @covers ::assocStreamToMedia()
     * @covers ::checkRequestForUpload()
     * @dataProvider uploadProvider()
     */
    public function testUpload(array $requestData, $expectedException, bool $uploaded) : void
    {
        // if upload failed, verify exception
        if ($expectedException != null) {
            $this->expectException(get_class($expectedException));
            $this->expectExceptionCode($expectedException->getCode());
            $this->expectExceptionMessage($expectedException->getMessage());
        }

        // get api client (+auth)
        $this->setupApi();

        // do component call
        $this->Modules->upload($requestData);

        // if upload ok, verify ID is not null
        if ($uploaded) {
            static::assertArrayHasKey('id', $requestData);

            // test upload of another file to change stream
            $name = 'test2.png';
            $file = getcwd() . sprintf('/tests/files/%s', $name);
            $type = mime_content_type($file);
            $requestData = [
                'file' => [
                    'name' => $name,
                    'tmp_name' => $file,
                    'type' => $type,
                    'error' => UPLOAD_ERR_OK,
                ],
                'model-type' => 'images',
                'id' => $requestData['id'],
            ];
            $this->Modules->upload($requestData);
            static::assertArrayHasKey('id', $requestData);
        } else {
            static::assertFalse(isset($requestData['id']));
        }
    }

    /**
     * Test `removeStream` method
     *
     * @return void
     *
     * @covers ::removeStream()
     */
    public function testRemoveStreamWhenThereIsNoStream()
    {
        $mockId = '99';
        $requestData = [
            'id' => $mockId,
            'model-type' => 'images',
        ];

        $apiClient = $this->getMockBuilder(BEditaClient::class)
            ->setConstructorArgs(['https://api.example.org'])
            ->getMock();

        $apiClient->method('get')
            ->with(sprintf('/images/%s/streams', $mockId))
            ->willReturn([]);

        ApiClientProvider::setApiClient($apiClient);
        $this->assertNull($this->Modules->removeStream($requestData));
    }

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
}
