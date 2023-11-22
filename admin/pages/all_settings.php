<?php
if(!$admin['r_admin']&&!$admin['r_demo']){echo'<p>'.M('error_access_denied').'</p>';exit;}
else AllSettings();