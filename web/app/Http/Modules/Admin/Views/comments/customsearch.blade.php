<?php
if(isset($cmnt)){ ?>
@foreach($cmnt as $c)
    {{ $c->comments}}
    {{ $c->comment_group_id }}
@endforeach
<?php } else {
    echo "kuch bhi nahi hua";
}
?>