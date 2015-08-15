{{
/**
*    This file is part of CIOpenReview - free review software licensed under the GNU General Public License version 2
*    Copyright (c) 2015 CIOpenReview.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
*    http://CIOpenReview.com
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
<!-- START OF 'CONTENT' SECTION -->
<div id="content">
    <h2 class="heading">{{= lang('contact_page_heading') }}</h2>

    <form class="contact" id="form" name="form" method="post" action="{{= base_url() . 'contact' }}">
        <div class="contactblock">
            <div class="contactleft">
                <label>{{= lang('contact_page_name') }}
                </label>
            </div>
            <div class="contactright">
                <input class="form_input" size="25" type="text" name="name" id="name" value="{{= set_value('name') }}"/>
                {{= form_error('name') }}
            </div>
        </div>
        <div class="contactblock">
            <div class="contactleft">
                <label>{{= lang('contact_page_email') }}
                </label>
            </div>
            <div class="contactright">
                <input class="form_input" size="40" type="text" name="email" id="email"
                       value="{{= set_value('email') }}"/>
                {{= form_error('email') }}
            </div>
        </div>
        <div class="contactblock">
            <div class="contactleft">
                <label>{{= lang('contact_page_message') }}
                </label>
            </div>
            <div class="contactright">
                <textarea class="form_input" cols="40" rows="10" name="message"
                          id="message">{{= set_value('message') }}</textarea>
                {{= form_error('message') }}
            </div>
        </div>
        <div class="contactblock">
            <div class="contactleft">
                <label>{{= lang('contact_page_captcha') }}
                </label>
            </div>
            <div class="contactright">
                <input class="form_input" size="40" type="text" name="captcha" id="captcha" value=""/>
                {{= form_error('captcha') }}
                <span class="captcha">{{= $captcha_image }}</span>
                {{ if ($error_message): }}
                <p id="error">{{= $error_message }}</p>
                {{ endif }}
            </div>
        </div>
        <div class="contactblock">
            <div class="contactleft"></div>
            <div class="contactright">
                <input id="contact_button" type="submit" name="contact_submit"
                       value="{{= lang('contact_page_form_submit_button') }}"/>
            </div>
        </div>

    </form>
</div>
<!-- END OF 'CONTENT' SECTION -->
