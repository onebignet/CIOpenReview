{{
/**
* CIOpenReview
*
* An Open Source Review Site Script
*
* @package        CIOpenReview
* @subpackage          manager
* @author        CIOpenReview.com
* @copyright           Copyright (c) 2015 MindBrite.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
* @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
* @link        http://ciopenreview.com
*/
// ------------------------------------------------------------------------
//
/**    This file is part of CIOpenReview.
*
*    CIOpenReview is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 2 of the License, or
*    (at your option) any later version.
*
*    CIOpenReview is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with CIOpenReview.  If not, see <http://www.gnu.org/licenses/>.
*/

}}
<div id="content">
    {{ if($message!=''): }}
    <div class="callout callout-success">
        <p>{{= $message }}</p>
    </div>
    {{ endif }}

    <div class="box">

        <div class="box-header">
            <h3 class="box-title">{{= lang('manager_maintenance_title') }}</h3>
            <div class="box-tools">
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_update_sitemap') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_update_sitemap_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="update_sitemap" id="button"
                               value="{{= lang('manager_maintenance_update_sitemap_button') }}"/>
                    </div>
                </form>
            </div>
            <div class="row">
                <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
                      name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_delete_session') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_delete_session_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="session_submit" id="button"
                               value="{{= lang('manager_maintenance_delete_session_button') }}"/>
                    </div>
                </form>
            </div>
            <div class="row">
                <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
                      name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_delete_log') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_delete_log_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="log_submit" id="button"
                               value="{{= lang('manager_maintenance_delete_log_button') }}"/>
                    </div>
                </form>
            </div>
            <div class="row">
                <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
                      name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_delete_cache') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_delete_cache_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="cache_submit" id="button"
                               value="{{= lang('manager_maintenance_delete_cache_button') }}"/>
                    </div>
                </form>
            </div>
            <div class="row">
                <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
                      name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_repair') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_repair_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="repair_submit" id="button"
                               value="{{= lang('manager_maintenance_repair_button') }}"/>
                    </div>
                </form>
            </div>
            <div class="row">
                <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
                      name="form" method="post" enctype="multipart/form-data"
                      action="{{= base_url() . 'manager/maintenance' }}">
                    <div class="col-md-9">
                        <label>{{= lang('manager_maintenance_update_db') }}
                        </label>
                        <p class="help-block">{{= lang('manager_maintenance_update_db_info') }}</p>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="repair_submit" id="button"
                               value="{{= lang('manager_maintenance_update_db_button') }}"/>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>