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

namespace Google\Service\AndroidPublisher;

class CreateDraftAppRecoveryRequest extends \Google\Model
{
  /**
   * @var RemoteInAppUpdate
   */
  public $remoteInAppUpdate;
  protected $remoteInAppUpdateType = RemoteInAppUpdate::class;
  protected $remoteInAppUpdateDataType = '';
  /**
   * @var Targeting
   */
  public $targeting;
  protected $targetingType = Targeting::class;
  protected $targetingDataType = '';

  /**
   * @param RemoteInAppUpdate
   */
  public function setRemoteInAppUpdate(RemoteInAppUpdate $remoteInAppUpdate)
  {
    $this->remoteInAppUpdate = $remoteInAppUpdate;
  }
  /**
   * @return RemoteInAppUpdate
   */
  public function getRemoteInAppUpdate()
  {
    return $this->remoteInAppUpdate;
  }
  /**
   * @param Targeting
   */
  public function setTargeting(Targeting $targeting)
  {
    $this->targeting = $targeting;
  }
  /**
   * @return Targeting
   */
  public function getTargeting()
  {
    return $this->targeting;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateDraftAppRecoveryRequest::class, 'Google_Service_AndroidPublisher_CreateDraftAppRecoveryRequest');
