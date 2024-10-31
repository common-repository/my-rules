<?php
/*CSS Document*/
?>
<style type="text/css">
    #wpNotificationBar {
        position: fixed;
        z-index: 99999999;
		font-family:Tahoma, Geneva, sans-serif;
		font-size:15px;
        top: 0;
        left: 0;
        right: 0;
        background: <?php echo(esc_attr(get_option('my_rulues_notification_bar_color'))); ?>;
		color:<?php echo(esc_attr(get_option('my_rulues_notification_text_color'))); ?>;
        text-align: center;
        line-height: 2.0;
        overflow: hidden; 
        -webkit-box-shadow: 0 0 5px black;
        -moz-box-shadow:    0 0 5px black;
        box-shadow:         0 0 5px black;
    }
	#closeThisBar
	{
		position:absolute;
		float:right;
		right: 5px;
	}
.myrules_close {
  font-size: 20px;
  font-weight: bold;
  line-height: 18px;
  color: <?php echo(esc_attr(get_option('my_rulues_close_color'))); ?>;
  opacity: 0.2;
  filter: alpha(opacity=20);
  text-decoration: none;
}
.myrules_close:hover {
  color: #000000;
  text-decoration: none;
  opacity: 0.4;
  filter: alpha(opacity=40);
  cursor: pointer;
}
.class_label {
	display: inline-block;
	cursor: pointer;
	position: relative;
	padding-left: 25px;
	margin-right: 15px;
	font-size: 13px;
}
input[type=radio],
input[type=checkbox] {
	display: none;
}
.class_label:before {
	content: "";
	display: inline-block;

	width: 16px;
	height: 16px;

	margin-right: 10px;
	position: absolute;
	left: 0;
	bottom: 1px;
	background-color: #aaa;
	box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);
}

.radio label:before {
	border-radius: 8px;
}
.checkbox label {
	margin-bottom: 5px;
}
.checkbox label:before {
    border-radius: 3px;
}

input[type=radio]:checked + label:before {
    content: "\2022";
    color: #f3f3f3;
    font-size: 30px;
    text-align: center;
    line-height: 18px;
}

input[type=checkbox]:checked + label:before {
	content: "\2713";
	text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
	font-size: 15px;
	color: #f3f3f3;
	text-align: center;
    line-height: 15px;
}}
</style>
	
