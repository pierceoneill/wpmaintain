<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace MetFormProVendor\Google\Service\Sheets;

class Color extends \MetFormProVendor\Google\Model
{
    public $alpha;
    public $blue;
    public $green;
    public $red;
    public function setAlpha($alpha)
    {
        $this->alpha = $alpha;
    }
    public function getAlpha()
    {
        return $this->alpha;
    }
    public function setBlue($blue)
    {
        $this->blue = $blue;
    }
    public function getBlue()
    {
        return $this->blue;
    }
    public function setGreen($green)
    {
        $this->green = $green;
    }
    public function getGreen()
    {
        return $this->green;
    }
    public function setRed($red)
    {
        $this->red = $red;
    }
    public function getRed()
    {
        return $this->red;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Color::class, 'MetFormProVendor\\Google_Service_Sheets_Color');
