<?php
header( 'Content-Disposition: attachment; filename= proof.pdf' );
header( 'Content-Type: application/pdf' );
echo $content_for_layout;