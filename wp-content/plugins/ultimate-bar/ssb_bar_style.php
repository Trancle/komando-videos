<style type="text/css">

	#ssb_wrap{
		width: 100%;
		padding:<?php echo $ssb_bar_height; ?>px 0px <?php echo $ssb_bar_height; ?>px 0px;
		margin:0 auto;
		background:<?php echo $ssb_background_color; ?>;
		position: fixed;
		<?php echo $ssb_bar_position; ?>;
		
  		left: 0;
  		right: 0;
  		z-index: 99999;
	}
	#ssb_bar_opn{
		margin:0 auto;
		position: fixed;
		background:#000;
		color: #fff;
		padding: 5px;
		display: none;
		<?php echo $ssb_bar_position; ?>;
		cursor: pointer;
		opacity: .7;
	}
	#ssb_bar_opn:hover{
		opacity: .8;
	}


	
	#ssb_elem{
		float: right;
		width: 4%;
		text-align: center;

	}
	#ssb_elem2{
		float: left;
		width: 3%;
		text-align: center;
		margin-top: 7px;

	}
	#ssb_content{
		width: 93%;
		float: right;
		display: inline-block;
		text-align: center;
	}
	#ssb_text{
		font-size: <?php echo $ssb_bar_text_size; ?>px;
		font-family: <?php echo $ssb_bar_text_fontFamily; ?>;
		font-weight: normal;
		font-style: normal;
		margin: 0;
		margin-top: <?php echo $ssb_bar_text_size/2; ?>px;
		padding: 0;
	}
	#ssb_close_btn{
		background-color: #fff;
		opacity: .7;
		cursor: pointer;
		font-size:15px;
		padding:5px 3px 5px 3px;
		color: <?php echo $ssb_background_color; ?>;
		opacity: .6;
		text-decoration: none;
		font-weight: bold;
		font-family: arial;
	}
	#ssb_close_btn:hover{
		opacity: 1;
	}
	.ulb_logo{
		font-family: serif;
		font-size: <?php echo $ssb_bar_text_size; ?>px;
		font-weight: bold;
		padding:25%;
		background-color: #fff;
		color: <?php echo $ssb_background_color; ?>;
		opacity: .6;
		margin-left: 15px;
		text-decoration: none;
	}
	.ulb_logo:hover{
		opacity: 1;
		color: <?php echo $ssb_background_color; ?>;
	}

</style>