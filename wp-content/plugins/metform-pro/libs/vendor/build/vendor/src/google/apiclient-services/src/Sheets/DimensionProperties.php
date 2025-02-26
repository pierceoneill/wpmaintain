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

class DimensionProperties extends \MetFormProVendor\Google\Collection
{
    protected $collection_key = 'developerMetadata';
    protected $dataSourceColumnReferenceType = DataSourceColumnReference::class;
    protected $dataSourceColumnReferenceDataType = '';
    protected $developerMetadataType = DeveloperMetadata::class;
    protected $developerMetadataDataType = 'array';
    public $hiddenByFilter;
    public $hiddenByUser;
    public $pixelSize;
    /**
     * @param DataSourceColumnReference
     */
    public function setDataSourceColumnReference(DataSourceColumnReference $dataSourceColumnReference)
    {
        $this->dataSourceColumnReference = $dataSourceColumnReference;
    }
    /**
     * @return DataSourceColumnReference
     */
    public function getDataSourceColumnReference()
    {
        return $this->dataSourceColumnReference;
    }
    /**
     * @param DeveloperMetadata[]
     */
    public function setDeveloperMetadata($developerMetadata)
    {
        $this->developerMetadata = $developerMetadata;
    }
    /**
     * @return DeveloperMetadata[]
     */
    public function getDeveloperMetadata()
    {
        return $this->developerMetadata;
    }
    public function setHiddenByFilter($hiddenByFilter)
    {
        $this->hiddenByFilter = $hiddenByFilter;
    }
    public function getHiddenByFilter()
    {
        return $this->hiddenByFilter;
    }
    public function setHiddenByUser($hiddenByUser)
    {
        $this->hiddenByUser = $hiddenByUser;
    }
    public function getHiddenByUser()
    {
        return $this->hiddenByUser;
    }
    public function setPixelSize($pixelSize)
    {
        $this->pixelSize = $pixelSize;
    }
    public function getPixelSize()
    {
        return $this->pixelSize;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(DimensionProperties::class, 'MetFormProVendor\\Google_Service_Sheets_DimensionProperties');
