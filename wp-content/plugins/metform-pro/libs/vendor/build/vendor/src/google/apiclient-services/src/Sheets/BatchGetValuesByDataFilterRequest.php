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

class BatchGetValuesByDataFilterRequest extends \MetFormProVendor\Google\Collection
{
    protected $collection_key = 'dataFilters';
    protected $dataFiltersType = DataFilter::class;
    protected $dataFiltersDataType = 'array';
    public $dateTimeRenderOption;
    public $majorDimension;
    public $valueRenderOption;
    /**
     * @param DataFilter[]
     */
    public function setDataFilters($dataFilters)
    {
        $this->dataFilters = $dataFilters;
    }
    /**
     * @return DataFilter[]
     */
    public function getDataFilters()
    {
        return $this->dataFilters;
    }
    public function setDateTimeRenderOption($dateTimeRenderOption)
    {
        $this->dateTimeRenderOption = $dateTimeRenderOption;
    }
    public function getDateTimeRenderOption()
    {
        return $this->dateTimeRenderOption;
    }
    public function setMajorDimension($majorDimension)
    {
        $this->majorDimension = $majorDimension;
    }
    public function getMajorDimension()
    {
        return $this->majorDimension;
    }
    public function setValueRenderOption($valueRenderOption)
    {
        $this->valueRenderOption = $valueRenderOption;
    }
    public function getValueRenderOption()
    {
        return $this->valueRenderOption;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(BatchGetValuesByDataFilterRequest::class, 'MetFormProVendor\\Google_Service_Sheets_BatchGetValuesByDataFilterRequest');
