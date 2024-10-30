<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>
    
jQuery("document").ready(function($) {
    
    // ==================================================
   // Confirm before removing the data
   // ==================================================
   
$(".remove-form").click(function() {
    if (confirm("Are you sure you want to permanently remove this?"))
        return true;
    else
        return false;
}) ;
   
    
});
    
</script>