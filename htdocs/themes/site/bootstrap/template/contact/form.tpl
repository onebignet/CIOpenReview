<!-- START OF 'CONTENT' SECTION -->
<div class="page-header">
    <h2>{{= lang('contact_page_heading') }}</h2>
</div>
<form class="contact" id="form" name="form" method="post" action="{{= base_url() . 'contact' }}">
    <div class="col-sm-9">
        <label>{{= lang('contact_page_name') }}
        </label>
        <input class="form-control" size="25" type="text" name="name" id="name" value="{{= set_value('name') }}"/>
        {{= form_error('name') }}
    </div>
    <div class="col-sm-9">
        <label>{{= lang('contact_page_email') }}
        </label>
        <input class="form-control" size="40" type="text" name="email" id="email" value="{{= set_value('email') }}"/>
        {{= form_error('email') }}
    </div>
    <div class="col-sm-9">
        <label>{{= lang('contact_page_message') }}
        </label>
    </div>
    <div class="col-sm-9">
        <textarea class="form-control" cols="40" rows="10" name="message"
                  id="message">{{= set_value('message') }}</textarea>
        {{= form_error('message') }}
    </div>
    <div class="col-sm-9">
        <label>{{= lang('contact_page_captcha') }}
        </label>
    </div>
    <div class="col-sm-6">
        <input class="form-control col-sm-5" size="40" type="text" name="captcha" id="captcha" value=""/>
    </div>
    <div class="col-sm-3">
        <span class="captcha col-sm-3">{{= $captcha_image }}</span>
    </div>
    {{= form_error('captcha') }}

    {{ if ($error_message): }}
    <div class="col-sm-9">
        <div class="text-error">{{= $error_message }}</div>
    </div>
    {{ endif }}
    <div class="col-sm-9">
        <p></p>
    </div>
    <div class="col-sm-9">
        <input id="contact_button" class="btn btn-success contact_submit" type="submit" name="contact_submit"
               value="{{= lang('contact_page_form_submit_button') }}"/>
    </div>

</form><!-- END OF 'CONTENT' SECTION -->
