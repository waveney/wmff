<?php
  include_once("int/fest.php");

  dohead("Mailing List",["//cdn-images.mailchimp.com/embedcode/classic-10_7.css", 'css/MailChimp.css'] ,1);

?>
<!-- Begin Mailchimp Signup Form -->
<div id="mc_embed_signup">
<form action="https://wimbornefolk.us19.list-manage.com/subscribe/post?u=05c2a1d96eaee5c1536d388cc&amp;id=0272065cc3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
<h2>Subscribe to our mailing list</h2>
<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>
<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
<div class="mc-field-group">
<label for="mce-FNAME">First Name  <span class="asterisk">*</span>
</label>
<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
</div>
<div class="mc-field-group">
<label for="mce-LNAME">Last Name  <span class="asterisk">*</span>
</label>
<input type="text" value="" name="LNAME" class="required" id="mce-LNAME">
</div>
<div class="mc-field-group">
<label for="mce-MMERGE3">Location  <span class="asterisk">*</span>
</label>
<input type="text" value="" name="MMERGE3" class="required" id="mce-MMERGE3">
</div>
<div id="mergeRow-gdpr" class="mergeRow gdpr-mergeRow content__gdprBlock mc-field-group">
    <div class="content__gdpr">
        <label>Marketing Permissions</label>
        <p>Please select all the ways you would like to hear from Wimborne Minster Folk Festival:</p>
        <fieldset class="mc_fieldset gdprRequired mc-field-group" name="interestgroup_field">
<label class="checkbox subfield" for="gdpr_28267"><input type="checkbox" id="gdpr_28267" name="gdpr[28267]" value="Y" class="av-checkbox "><span>Email</span> </label>
        </fieldset>
        <p>You can unsubscribe at any time by clicking the link in the footer of our emails. For information about our privacy practices, please visit our website.</p>
    </div>
    <div class="content__gdprLegal">
        <p>We use Mailchimp as our marketing platform. By clicking below to subscribe, you acknowledge that your information will be transferred to Mailchimp for processing. <a href="https://mailchimp.com/legal/" target="_blank">Learn more about Mailchimp's privacy practices here.</a></p>
    </div>
</div>
<div id="mce-responses" class="clear">
<div class="response" id="mce-error-response" style="display:none"></div>
<div class="response" id="mce-success-response" style="display:none"></div>
</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_05c2a1d96eaee5c1536d388cc_0272065cc3" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" ></div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->

<?php
  dotail();
?>
