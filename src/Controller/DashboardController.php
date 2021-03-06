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
namespace App\Controller;

/**
 * Dashboard controller.
 */
class DashboardController extends AppController
{
    /**
     * {@inheritDoc}
     */
    public function initialize() : void
    {
        parent::initialize();

        // force `GET /home` reload
        $this->Modules->setConfig('clearHomeCache', true);
    }

    /**
     * Displays dashboard.
     *
     * @return void
     */
    public function index() : void
    {
        $this->request->allowMethod(['get']);
    }
}
