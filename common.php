<?php

function alert($msg, $location)
{
    echo "<script type='text/javascript'>alert('$msg');window.location.href='$location'</script>";
}

?>