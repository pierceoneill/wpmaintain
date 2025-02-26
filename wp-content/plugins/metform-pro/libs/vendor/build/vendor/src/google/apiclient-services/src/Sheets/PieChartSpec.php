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

class PieChartSpec extends \MetFormProVendor\Google\Model
{
    protected $domainType = ChartData::class;
    protected $domainDataType = '';
    public $legendPosition;
    public $pieHole;
    protected $seriesType = ChartData::class;
    protected $seriesDataType = '';
    public $threeDimensional;
    /**
     * @param ChartData
     */
    public function setDomain(ChartData $domain)
    {
        $this->domain = $domain;
    }
    /**
     * @return ChartData
     */
    public function getDomain()
    {
        return $this->domain;
    }
    public function setLegendPosition($legendPosition)
    {
        $this->legendPosition = $legendPosition;
    }
    public function getLegendPosition()
    {
        return $this->legendPosition;
    }
    public function setPieHole($pieHole)
    {
        $this->pieHole = $pieHole;
    }
    public function getPieHole()
    {
        return $this->pieHole;
    }
    /**
     * @param ChartData
     */
    public function setSeries(ChartData $series)
    {
        $this->series = $series;
    }
    /**
     * @return ChartData
     */
    public function getSeries()
    {
        return $this->series;
    }
    public function setThreeDimensional($threeDimensional)
    {
        $this->threeDimensional = $threeDimensional;
    }
    public function getThreeDimensional()
    {
        return $this->threeDimensional;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(PieChartSpec::class, 'MetFormProVendor\\Google_Service_Sheets_PieChartSpec');
