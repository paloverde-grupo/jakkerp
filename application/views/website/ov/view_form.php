<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-skins.min.css">

		<!-- SmartAdmin RTL Support is under construction
			 This RTL CSS will be released in version 1.5
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-rtl.min.css"> -->

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="/template/css/demo.min.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/template/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/template/img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="/template/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/template/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/template/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/template/img/splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="/template/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="/template/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="/template/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

	</head>
	<body class="">
		<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>
				<!-- END LOGO PLACEHOLDER -->

				<!-- Note: The activity badge color changes when clicked and resets the number to 0
				Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
				<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>

				<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
				<div class="ajax-dropdown">

					<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/mail.html">
							Msgs (14) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/notifications.html">
							notify (3) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/tasks.html">
							Tasks (4) </label>
					</div>

					<!-- notification content -->
					<div class="ajax-notifications custom-scroll">

						<div class="alert alert-transparent">
							<h4>Click a button to show messages here</h4>
							This blank page message helps protect your privacy, or you can show the first message here automatically.
						</div>

						<i class="fa fa-lock fa-4x fa-border"></i>

					</div>
					<!-- end notification content -->

					<!-- footer: refresh area -->
					<span> Last updated on: 12/12/2013 9:43AM
						<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
							<i class="fa fa-refresh"></i>
						</button> </span>
					<!-- end footer -->

				</div>
				<!-- END AJAX-DROPDOWN -->
			</div>

			<!-- projects dropdown -->
			<div class="project-context hidden-xs">

				<span class="label">Projects:</span>
				<span class="project-selector dropdown-toggle" data-toggle="dropdown">Recent projects <i class="fa fa-angle-down"></i></span>

				<!-- Suggestion: populate this list with fetch and push technique -->
				<ul class="dropdown-menu">
					<li>
						<a href="javascript:void(0);">Online e-merchant management system - attaching integration with the iOS</a>
					</li>
					<li>
						<a href="javascript:void(0);">Notes on pipeline upgradee</a>
					</li>
					<li>
						<a href="javascript:void(0);">Assesment Report for merchant account</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);"><i class="fa fa-power-off"></i> Clear</a>
					</li>
				</ul>
				<!-- end dropdown-menu-->

			</div>
			<!-- end projects dropdown -->

			<!-- pulled right: nav area -->
			<div class="pull-right">
				
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				<!-- Top menu profile link : this shows only when top menu is active -->
				<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
					<li class="">
						<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
							<img src="img/avatars/sunny.png" alt="John Doe" class="online" />  
						</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
							</li>
						</ul>
					</li>
				</ul>

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="login.html" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->

				<!-- input: search field -->
				<form action="search.html" class="header-search pull-right">
					<input id="search-fld"  type="text" name="param" placeholder="Find reports and more" data-autocomplete='[
					"ActionScript",
					"AppleScript",
					"Asp",
					"BASIC",
					"C",
					"C++",
					"Clojure",
					"COBOL",
					"ColdFusion",
					"Erlang",
					"Fortran",
					"Groovy",
					"Haskell",
					"Java",
					"JavaScript",
					"Lisp",
					"Perl",
					"PHP",
					"Python",
					"Ruby",
					"Scala",
					"Scheme"]'>
					<button type="submit">
						<i class="fa fa-search"></i>
					</button>
					<a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
				</form>
				<!-- end input: search field -->

				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<!-- end fullscreen button -->
				
				<!-- #Voice Command: Start Speech -->
				<div id="speech-btn" class="btn-header transparent pull-right hidden-sm hidden-xs">
					<div> 
						<a href="javascript:void(0)" title="Voice Command" data-action="voiceCommand"><i class="fa fa-microphone"></i></a> 
						<div class="popover bottom"><div class="arrow"></div>
							<div class="popover-content">
								<h4 class="vc-title">Voice command activated <br><small>Please speak clearly into the mic</small></h4>
								<h4 class="vc-title-error text-center">
									<i class="fa fa-microphone-slash"></i> Voice command failed
									<br><small class="txt-color-red">Must <strong>"Allow"</strong> Microphone</small>
									<br><small class="txt-color-red">Must have <strong>Internet Connection</strong></small>
								</h4>
								<a href="javascript:void(0);" class="btn btn-success" onclick="commands.help()">See Commands</a> 
								<a href="javascript:void(0);" class="btn bg-color-purple txt-color-white" onclick="$('#speech-btn .popover').fadeOut(50);">Close Popup</a> 
							</div>
						</div>
					</div>
				</div>
				<!-- end voice command -->

				<!-- multiple lang dropdown : find all flags in the flags page -->
				<ul class="header-dropdown-list hidden-xs">
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="img/blank.gif" class="flag flag-us" alt="United States"> <span> English (US) </span> <i class="fa fa-angle-down"></i> </a>
						<ul class="dropdown-menu pull-right">
							<li class="active">
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-us" alt="United States"> English (US)</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-fr" alt="France"> Français</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-es" alt="Spanish"> Español</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-de" alt="German"> Deutsch</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-jp" alt="Japan"> 日本語</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-cn" alt="China"> 中文</a>
							</li>	
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-it" alt="Italy"> Italiano</a>
							</li>	
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-pt" alt="Portugal"> Portugal</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-ru" alt="Russia"> Русский язык</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-kp" alt="Korea"> 한국어</a>
							</li>						
							
						</ul>
					</li>
				</ul>
				<!-- end multiple lang -->

			</div>
			<!-- end pulled right: nav area -->

		</header>
		<!-- END HEADER -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home></li><li>Forms</li><li>Smart Form Layouts</li>
				</ol>
				<!-- end breadcrumb -->

				<!-- You can also add more buttons to the
				ribbon for further usability

				Example below:

				<span class="ribbon-button-alignment pull-right">
				<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
				<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
				<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
				</span> -->

			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">


<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-pencil-square-o"></i> 
				Forms
			<span>>  
				Form Layouts
			</span>
		</h1>
	</div>
	
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<!-- Button trigger modal -->
		<a data-toggle="modal" href="#myModal" class="btn btn-success btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> Launch form modal</a>
	</div>
</div>

<div class="alert alert-block alert-success">
	<a class="close" data-dismiss="alert" href="#">×</a>
	<h4 class="alert-heading"><i class="fa fa-check-square-o"></i> Check validation!</h4>
	<p>
		You may also check the form validation by clicking on the form action button. Please try and see the results below!
	</p>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">


	<!-- START ROW -->

	<div class="row">

		<!-- NEW COL START -->
		<article class="col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Checkout Form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form id="checkout-form" class="smart-form" novalidate="novalidate">

							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="fname" placeholder="First name">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="lname" placeholder="Last name">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input type="email" name="email" placeholder="E-mail">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-phone"></i>
											<input type="tel" name="phone" placeholder="Phone" data-mask="(999) 999-9999">
										</label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-5">
										<label class="select">
											<select name="country">
												<option value="0" selected="" disabled="">Country</option>
												<option value="244">Aaland Islands</option>
												<option value="1">Afghanistan</option>
												<option value="2">Albania</option>
												<option value="3">Algeria</option>
												<option value="4">American Samoa</option>
												<option value="5">Andorra</option>
												<option value="6">Angola</option>
												<option value="7">Anguilla</option>
												<option value="8">Antarctica</option>
												<option value="9">Antigua and Barbuda</option>
												<option value="10">Argentina</option>
												<option value="11">Armenia</option>
												<option value="12">Aruba</option>
												<option value="13">Australia</option>
												<option value="14">Austria</option>
												<option value="15">Azerbaijan</option>
												<option value="16">Bahamas</option>
												<option value="17">Bahrain</option>
												<option value="18">Bangladesh</option>
												<option value="19">Barbados</option>
												<option value="20">Belarus</option>
												<option value="21">Belgium</option>
												<option value="22">Belize</option>
												<option value="23">Benin</option>
												<option value="24">Bermuda</option>
												<option value="25">Bhutan</option>
												<option value="26">Bolivia</option>
												<option value="245">Bonaire, Sint Eustatius and Saba</option>
												<option value="27">Bosnia and Herzegovina</option>
												<option value="28">Botswana</option>
												<option value="29">Bouvet Island</option>
												<option value="30">Brazil</option>
												<option value="31">British Indian Ocean Territory</option>
												<option value="32">Brunei Darussalam</option>
												<option value="33">Bulgaria</option>
												<option value="34">Burkina Faso</option>
												<option value="35">Burundi</option>
												<option value="36">Cambodia</option>
												<option value="37">Cameroon</option>
												<option value="38">Canada</option>
												<option value="251">Canary Islands</option>
												<option value="39">Cape Verde</option>
												<option value="40">Cayman Islands</option>
												<option value="41">Central African Republic</option>
												<option value="42">Chad</option>
												<option value="43">Chile</option>
												<option value="44">China</option>
												<option value="45">Christmas Island</option>
												<option value="46">Cocos (Keeling) Islands</option>
												<option value="47">Colombia</option>
												<option value="48">Comoros</option>
												<option value="49">Congo</option>
												<option value="50">Cook Islands</option>
												<option value="51">Costa Rica</option>
												<option value="52">Cote D'Ivoire</option>
												<option value="53">Croatia</option>
												<option value="54">Cuba</option>
												<option value="246">Curacao</option>
												<option value="55">Cyprus</option>
												<option value="56">Czech Republic</option>
												<option value="237">Democratic Republic of Congo</option>
												<option value="57">Denmark</option>
												<option value="58">Djibouti</option>
												<option value="59">Dominica</option>
												<option value="60">Dominican Republic</option>
												<option value="61">East Timor</option>
												<option value="62">Ecuador</option>
												<option value="63">Egypt</option>
												<option value="64">El Salvador</option>
												<option value="65">Equatorial Guinea</option>
												<option value="66">Eritrea</option>
												<option value="67">Estonia</option>
												<option value="68">Ethiopia</option>
												<option value="69">Falkland Islands (Malvinas)</option>
												<option value="70">Faroe Islands</option>
												<option value="71">Fiji</option>
												<option value="72">Finland</option>
												<option value="74">France, skypolitan</option>
												<option value="75">French Guiana</option>
												<option value="76">French Polynesia</option>
												<option value="77">French Southern Territories</option>
												<option value="126">FYROM</option>
												<option value="78">Gabon</option>
												<option value="79">Gambia</option>
												<option value="80">Georgia</option>
												<option value="81">Germany</option>
												<option value="82">Ghana</option>
												<option value="83">Gibraltar</option>
												<option value="84">Greece</option>
												<option value="85">Greenland</option>
												<option value="86">Grenada</option>
												<option value="87">Guadeloupe</option>
												<option value="88">Guam</option>
												<option value="89">Guatemala</option>
												<option value="241">Guernsey</option>
												<option value="90">Guinea</option>
												<option value="91">Guinea-Bissau</option>
												<option value="92">Guyana</option>
												<option value="93">Haiti</option>
												<option value="94">Heard and Mc Donald Islands</option>
												<option value="95">Honduras</option>
												<option value="96">Hong Kong</option>
												<option value="97">Hungary</option>
												<option value="98">Iceland</option>
												<option value="99">India</option>
												<option value="100">Indonesia</option>
												<option value="101">Iran (Islamic Republic of)</option>
												<option value="102">Iraq</option>
												<option value="103">Ireland</option>
												<option value="104">Israel</option>
												<option value="105">Italy</option>
												<option value="106">Jamaica</option>
												<option value="107">Japan</option>
												<option value="240">Jersey</option>
												<option value="108">Jordan</option>
												<option value="109">Kazakhstan</option>
												<option value="110">Kenya</option>
												<option value="111">Kiribati</option>
												<option value="113">Korea, Republic of</option>
												<option value="114">Kuwait</option>
												<option value="115">Kyrgyzstan</option>
												<option value="116">Lao People's Democratic Republic</option>
												<option value="117">Latvia</option>
												<option value="118">Lebanon</option>
												<option value="119">Lesotho</option>
												<option value="120">Liberia</option>
												<option value="121">Libyan Arab Jamahiriya</option>
												<option value="122">Liechtenstein</option>
												<option value="123">Lithuania</option>
												<option value="124">Luxembourg</option>
												<option value="125">Macau</option>
												<option value="127">Madagascar</option>
												<option value="128">Malawi</option>
												<option value="129">Malaysia</option>
												<option value="130">Maldives</option>
												<option value="131">Mali</option>
												<option value="132">Malta</option>
												<option value="133">Marshall Islands</option>
												<option value="134">Martinique</option>
												<option value="135">Mauritania</option>
												<option value="136">Mauritius</option>
												<option value="137">Mayotte</option>
												<option value="138">Mexico</option>
												<option value="139">Micronesia, Federated States of</option>
												<option value="140">Moldova, Republic of</option>
												<option value="141">Monaco</option>
												<option value="142">Mongolia</option>
												<option value="242">Montenegro</option>
												<option value="143">Montserrat</option>
												<option value="144">Morocco</option>
												<option value="145">Mozambique</option>
												<option value="146">Myanmar</option>
												<option value="147">Namibia</option>
												<option value="148">Nauru</option>
												<option value="149">Nepal</option>
												<option value="150">Netherlands</option>
												<option value="151">Netherlands Antilles</option>
												<option value="152">New Caledonia</option>
												<option value="153">New Zealand</option>
												<option value="154">Nicaragua</option>
												<option value="155">Niger</option>
												<option value="156">Nigeria</option>
												<option value="157">Niue</option>
												<option value="158">Norfolk Island</option>
												<option value="112">North Korea</option>
												<option value="159">Northern Mariana Islands</option>
												<option value="160">Norway</option>
												<option value="161">Oman</option>
												<option value="162">Pakistan</option>
												<option value="163">Palau</option>
												<option value="247">Palestinian Territory, Occupied</option>
												<option value="164">Panama</option>
												<option value="165">Papua New Guinea</option>
												<option value="166">Paraguay</option>
												<option value="167">Peru</option>
												<option value="168">Philippines</option>
												<option value="169">Pitcairn</option>
												<option value="170">Poland</option>
												<option value="171">Portugal</option>
												<option value="172">Puerto Rico</option>
												<option value="173">Qatar</option>
												<option value="174">Reunion</option>
												<option value="175">Romania</option>
												<option value="176">Russian Federation</option>
												<option value="177">Rwanda</option>
												<option value="178">Saint Kitts and Nevis</option>
												<option value="179">Saint Lucia</option>
												<option value="180">Saint Vincent and the Grenadines</option>
												<option value="181">Samoa</option>
												<option value="182">San Marino</option>
												<option value="183">Sao Tome and Principe</option>
												<option value="184">Saudi Arabia</option>
												<option value="185">Senegal</option>
												<option value="243">Serbia</option>
												<option value="186">Seychelles</option>
												<option value="187">Sierra Leone</option>
												<option value="188">Singapore</option>
												<option value="189">Slovak Republic</option>
												<option value="190">Slovenia</option>
												<option value="191">Solomon Islands</option>
												<option value="192">Somalia</option>
												<option value="193">South Africa</option>
												<option value="194">South Georgia &amp; South Sandwich Islands</option>
												<option value="248">South Sudan</option>
												<option value="195">Spain</option>
												<option value="196">Sri Lanka</option>
												<option value="249">St. Barthelemy</option>
												<option value="197">St. Helena</option>
												<option value="250">St. Martin (French part)</option>
												<option value="198">St. Pierre and Miquelon</option>
												<option value="199">Sudan</option>
												<option value="200">Suriname</option>
												<option value="201">Svalbard and Jan Mayen Islands</option>
												<option value="202">Swaziland</option>
												<option value="203">Sweden</option>
												<option value="204">Switzerland</option>
												<option value="205">Syrian Arab Republic</option>
												<option value="206">Taiwan</option>
												<option value="207">Tajikistan</option>
												<option value="208">Tanzania, United Republic of</option>
												<option value="209">Thailand</option>
												<option value="210">Togo</option>
												<option value="211">Tokelau</option>
												<option value="212">Tonga</option>
												<option value="213">Trinidad and Tobago</option>
												<option value="214">Tunisia</option>
												<option value="215">Turkey</option>
												<option value="216">Turkmenistan</option>
												<option value="217">Turks and Caicos Islands</option>
												<option value="218">Tuvalu</option>
												<option value="219">Uganda</option>
												<option value="220">Ukraine</option>
												<option value="221">United Arab Emirates</option>
												<option value="222">United Kingdom</option>
												<option value="223">United States</option>
												<option value="224">United States Minor Outlying Islands</option>
												<option value="225">Uruguay</option>
												<option value="226">Uzbekistan</option>
												<option value="227">Vanuatu</option>
												<option value="228">Vatican City State (Holy See)</option>
												<option value="229">Venezuela</option>
												<option value="230">Viet Nam</option>
												<option value="231">Virgin Islands (British)</option>
												<option value="232">Virgin Islands (U.S.)</option>
												<option value="233">Wallis and Futuna Islands</option>
												<option value="234">Western Sahara</option>
												<option value="235">Yemen</option>
												<option value="238">Zambia</option>
												<option value="239">Zimbabwe</option>
											</select> <i></i> </label>
									</section>

									<section class="col col-4">
										<label class="input">
											<input type="text" name="city" placeholder="City">
										</label>
									</section>

									<section class="col col-3">
										<label class="input">
											<input type="text" name="code" placeholder="Post code">
										</label>
									</section>
								</div>

								<section>
									<label for="address2" class="input">
										<input type="text" name="address2" id="address2" placeholder="Address">
									</label>
								</section>

								<section>
									<label class="textarea"> 										
										<textarea rows="3" name="info" placeholder="Additional info"></textarea> 
									</label>
								</section>
							</fieldset>

							<fieldset>
								<section>
									<div class="inline-group">
										<label class="radio">
											<input type="radio" name="radio-inline" checked="">
											<i></i>Visa</label>
										<label class="radio">
											<input type="radio" name="radio-inline">
											<i></i>MasterCard</label>
										<label class="radio">
											<input type="radio" name="radio-inline">
											<i></i>American Express</label>
									</div>
								</section>

								<section>
									<label class="input">
										<input type="text" name="name" placeholder="Name on card">
									</label>
								</section>

								<div class="row">
									<section class="col col-10">
										<label class="input">
											<input type="text" name="card" placeholder="Card number" data-mask="9999-9999-9999-9999">
										</label>
									</section>
									<section class="col col-2">
										<label class="input">
											<input type="text" name="cvv" placeholder="CVV2" data-mask="999">
										</label>
									</section>
								</div>

								<div class="row">
									<label class="label col col-4">Expiration date</label>
									<section class="col col-5">
										<label class="select">
											<select name="month">
												<option value="0" selected="" disabled="">Month</option>
												<option value="1">January</option>
												<option value="1">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6">June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-3">
										<label class="input">
											<input type="text" name="year" placeholder="Year" data-mask="2099">
										</label>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
						</form>

					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Registration form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form id="order-form" class="smart-form" novalidate="novalidate">
							<header>
								Order services
							</header>

							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="name" placeholder="Name">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-briefcase"></i>
											<input type="text" name="company" placeholder="Company">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
											<input type="email" name="email" placeholder="E-mail">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="tel" name="phone" placeholder="Phone" data-mask="(999) 999-9999">
										</label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="select">
											<select name="interested">
												<option value="0" selected="" disabled="">Interested in</option>
												<option value="1">design</option>
												<option value="1">development</option>
												<option value="2">illustration</option>
												<option value="2">branding</option>
												<option value="3">video</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-6">
										<label class="select">
											<select name="budget">
												<option value="0" selected="" disabled="">Budget</option>
												<option value="1">less than 5000$</option>
												<option value="2">5000$ - 10000$</option>
												<option value="3">10000$ - 20000$</option>
												<option value="4">more than 20000$</option>
											</select> <i></i> </label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="startdate" id="startdate" placeholder="Expected start date">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="finishdate" id="finishdate" placeholder="Expected finish date">
										</label>
									</section>
								</div>

								<section>
									<div class="input input-file">
										<span class="button"><input id="file2" type="file" name="file2" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include some files" readonly="">
									</div>
								</section>

								<section>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										
										<textarea rows="5" name="comment" placeholder="Tell us about your project"></textarea> 
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
						</form>

					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->				

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-7" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Review form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form id="review-form" class="smart-form">
							<header>
								Review form
							</header>

							<fieldset>
								<section>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="text" name="name" id="name" placeholder="Your name">
									</label>
								</section>

								<section>
									<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
										<input type="email" name="email" id="email" placeholder="Your e-mail">
									</label>
								</section>

								<section>
									<label class="label"></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										
										<textarea rows="3" name="review" id="review" placeholder="Text of the review"></textarea> 
									</label>
								</section>

								<section>
									<div class="rating">
										<input type="radio" name="quality" id="quality-5">
										<label for="quality-5"><i class="fa fa-star"></i></label>
										<input type="radio" name="quality" id="quality-4">
										<label for="quality-4"><i class="fa fa-star"></i></label>
										<input type="radio" name="quality" id="quality-3">
										<label for="quality-3"><i class="fa fa-star"></i></label>
										<input type="radio" name="quality" id="quality-2">
										<label for="quality-2"><i class="fa fa-star"></i></label>
										<input type="radio" name="quality" id="quality-1">
										<label for="quality-1"><i class="fa fa-star"></i></label>
										Quality of the product
									</div>

									<div class="rating">
										<input type="radio" name="reliability" id="reliability-5">
										<label for="reliability-5"><i class="fa fa-star"></i></label>
										<input type="radio" name="reliability" id="reliability-4">
										<label for="reliability-4"><i class="fa fa-star"></i></label>
										<input type="radio" name="reliability" id="reliability-3">
										<label for="reliability-3"><i class="fa fa-star"></i></label>
										<input type="radio" name="reliability" id="reliability-2">
										<label for="reliability-2"><i class="fa fa-star"></i></label>
										<input type="radio" name="reliability" id="reliability-1">
										<label for="reliability-1"><i class="fa fa-star"></i></label>
										Reliability of the product
									</div>

									<div class="rating">
										<input type="radio" name="overall" id="overall-5">
										<label for="overall-5"><i class="fa fa-star"></i></label>
										<input type="radio" name="overall" id="overall-4">
										<label for="overall-4"><i class="fa fa-star"></i></label>
										<input type="radio" name="overall" id="overall-3">
										<label for="overall-3"><i class="fa fa-star"></i></label>
										<input type="radio" name="overall" id="overall-2">
										<label for="overall-2"><i class="fa fa-star"></i></label>
										<input type="radio" name="overall" id="overall-1">
										<label for="overall-1"><i class="fa fa-star"></i></label>
										Overall rating
									</div>
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
						</form>						
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->	

		</article>
		<!-- END COL -->

		<!-- NEW COL START -->
		<article class="col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Registration form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form id="smart-form-register" class="smart-form">
							<header>
								Registration form
							</header>

							<fieldset>
								<section>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="text" name="username" placeholder="Username">
										<b class="tooltip tooltip-bottom-right">Needed to enter the website</b> </label>
								</section>
								
								
								
								<section>
									<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
										<input type="email" name="email" placeholder="Email address">
										<b class="tooltip tooltip-bottom-right">Needed to verify your account</b> </label>
								</section>

								<section>
									<label class="input"> <i class="icon-append fa fa-lock"></i>
										<input type="password" name="password" placeholder="Password" id="password">
										<b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
								</section>

								<section>
									<label class="input"> <i class="icon-append fa fa-lock"></i>
										<input type="password" name="passwordConfirm" placeholder="Confirm password">
										<b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
								</section>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input">
											<input type="text" name="firstname" placeholder="First name">
										</label>
									</section>
									<section class="col col-6">
										<label class="input">
											<input type="text" name="lastname" placeholder="Last name">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">
										<label class="select">
											<select name="gender">
												<option value="0" selected="" disabled="">Gender</option>
												<option value="1">Male</option>
												<option value="2">Female</option>
												<option value="3">Prefer not to answer</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="request" placeholder="Request activation on" class="datepicker" data-dateformat='dd/mm/yy'>
										</label>
									</section>
								</div>	

								<section>
									<label class="checkbox">
										<input type="checkbox" name="subscription" id="subscription">
										<i></i>I want to receive news and special offers</label>
									<label class="checkbox">
										<input type="checkbox" name="terms" id="terms">
										<i></i>I agree with the Terms and Conditions</label>
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
						</form>						
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Comment form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form action="php/demo-comment.php" method="post" id="comment-form" class="smart-form">
							<header>
								Comment form
							</header>

							<fieldset>
								<div class="row">
									<section class="col col-4">
										<label class="label">Name</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="name">
										</label>
									</section>
									<section class="col col-4">
										<label class="label">E-mail</label>
										<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
											<input type="email" name="email">
										</label>
									</section>
									<section class="col col-4">
										<label class="label">Website</label>
										<label class="input"> <i class="icon-append fa fa-globe"></i>
											<input type="url" name="url">
										</label>
									</section>
								</div>

								<section>
									<label class="label">Comment</label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										<textarea rows="4" name="comment"></textarea> </label>
									<div class="note">
										You may use these HTML tags and attributes: &lt;a href="" title=""&gt;, &lt;abbr title=""&gt;, &lt;acronym title=""&gt;, &lt;b&gt;, &lt;blockquote cite=""&gt;, &lt;cite&gt;, &lt;code&gt;, &lt;del datetime=""&gt;, &lt;em&gt;, &lt;i&gt;, &lt;q cite=""&gt;, &lt;strike&gt;, &lt;strong&gt;.
									</div>
								</section>
							</fieldset>

							<footer>
								<button type="submit" name="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>

							<div class="message">
								<i class="fa fa-check fa-lg"></i>
								<p>
									Your comment was successfully added!
								</p>
							</div>
						</form>
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
				
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Contacts form </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form action="demo-contacts.php" method="post" id="contact-form" class="smart-form">
							<header>Contacts form</header>
							
							<fieldset>					
								<div class="row">
									<section class="col col-6">
										<label class="label">Name</label>
										<label class="input">
											<i class="icon-append fa fa-user"></i>
											<input type="text" name="named" id="named">
										</label>
									</section>
									<section class="col col-6">
										<label class="label">E-mail</label>
										<label class="input">
											<i class="icon-append fa fa-envelope-o"></i>
											<input type="email" name="emaild" id="emaild">
										</label>
									</section>
								</div>
								
								<section>
									<label class="label">Subject</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="subject" id="subject">
									</label>
								</section>
								
								<section>
									<label class="label">Message</label>
									<label class="textarea">
										<i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="message" id="message"></textarea>
									</label>
								</section>
								
								<section>
									<label class="checkbox"><input type="checkbox" name="copy" id="copy"><i></i>Send a copy to my e-mail address</label>
								</section>
							</fieldset>
							
							<footer>
								<button type="submit" class="btn btn-primary">Validate Form</button>
							</footer>
							
							<div class="message">
								<i class="fa fa-thumbs-up"></i>
								<p>Your message was successfully sent!</p>
							</div>
						</form>						
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->								


		</article>
		<!-- END COL -->		

	</div>

	<!-- END ROW -->

</section>
<!-- end widget grid -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					<img src="img/logo.png" width="150" alt="SmartAdmin">
				</h4>
			</div>
			<div class="modal-body no-padding">

				<form id="login-form" class="smart-form">

							<fieldset>
								<section>
									<div class="row">
										<label class="label col col-2">Username</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="email" name="email">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Password</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-lock"></i>
												<input type="password" name="password">
											</label>
											<div class="note">
												<a href="javascript:void(0)">Forgot password?</a>
											</div>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<div class="col col-2"></div>
										<div class="col col-10">
											<label class="checkbox">
												<input type="checkbox" name="remember" checked="">
												<i></i>Keep me logged in</label>
										</div>
									</div>
								</section>
							</fieldset>
							
							<footer>
								<button type="submit" class="btn btn-primary">
									Sign in
								</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Cancel
								</button>

							</footer>
						</form>						
						

			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white">SmartAdmin WebApp © 2013-2014</span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">
						<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong>52 mins ago &nbsp;</strong> </i>
						<div class="btn-group dropup">
							<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
								<i class="fa fa-link"></i> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right text-left">
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Download Progress</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 50%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Server Load</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 20%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<button class="btn btn-block btn-default">refresh</button>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE FOOTER -->

		<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
		<div id="shortcut">
			<ul>
				<li>
					<a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
				</li>
				<li>
					<a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
				</li>
				<li>
					<a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
				</li>
				<li>
					<a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
				</li>
				<li>
					<a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
				</li>
				<li>
					<a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
				</li>
			</ul>
		</div>
		<!-- END SHORTCUT AREA -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="/template/js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="/template/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="/template/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="/template/js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="/template/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="/template/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="/template/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="/template/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="/template/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="/template/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="/template/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="/template/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="/template/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="/template/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="/template/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="/template/js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- Demo purpose only -->
		<script src="/template/js/demo.min.js"></script>

		<!-- MAIN APP JS FILE -->
		<script src="/template/js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="/template/js/speech/voicecommand.min.js"></script>

		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
		

		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();

			var $checkoutForm = $('#checkout-form').validate({
			// Rules for form validation
				rules : {
					fname : {
						required : true
					},
					lname : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					phone : {
						required : true
					},
					country : {
						required : true
					},
					city : {
						required : true
					},
					code : {
						required : true,
						digits : true
					},
					address : {
						required : true
					},
					name : {
						required : true
					},
					card : {
						required : true,
						creditcard : true
					},
					cvv : {
						required : true,
						digits : true
					},
					month : {
						required : true
					},
					year : {
						required : true,
						digits : true
					}
				},
		
				// Messages for form validation
				messages : {
					fname : {
						required : 'Please enter your first name'
					},
					lname : {
						required : 'Please enter your last name'
					},
					email : {
						required : 'Please enter your email address',
						email : 'Please enter a VALID email address'
					},
					phone : {
						required : 'Please enter your phone number'
					},
					country : {
						required : 'Please select your country'
					},
					city : {
						required : 'Please enter your city'
					},
					code : {
						required : 'Please enter code',
						digits : 'Digits only please'
					},
					address : {
						required : 'Please enter your full address'
					},
					name : {
						required : 'Please enter name on your card'
					},
					card : {
						required : 'Please enter your card number'
					},
					cvv : {
						required : 'Enter CVV2',
						digits : 'Digits only'
					},
					month : {
						required : 'Select month'
					},
					year : {
						required : 'Enter year',
						digits : 'Digits only please'
					}
				},
		
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
					
			var $registerForm = $("#smart-form-register").validate({
	
				// Rules for form validation
				rules : {
					username : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					password : {
						required : true,
						minlength : 3,
						maxlength : 20
					},
					passwordConfirm : {
						required : true,
						minlength : 3,
						maxlength : 20,
						equalTo : '#password'
					},
					firstname : {
						required : true
					},
					lastname : {
						required : true
					},
					gender : {
						required : true
					},
					terms : {
						required : true
					}
				},
	
				// Messages for form validation
				messages : {
					login : {
						required : 'Please enter your login'
					},
					email : {
						required : 'Please enter your email address',
						email : 'Please enter a VALID email address'
					},
					password : {
						required : 'Please enter your password'
					},
					passwordConfirm : {
						required : 'Please enter your password one more time',
						equalTo : 'Please enter the same password as above'
					},
					firstname : {
						required : 'Please select your first name'
					},
					lastname : {
						required : 'Please select your last name'
					},
					gender : {
						required : 'Please select your gender'
					},
					terms : {
						required : 'You must agree with Terms and Conditions'
					}
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	
			var $reviewForm = $("#review-form").validate({
				// Rules for form validation
				rules : {
					name : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					review : {
						required : true,
						minlength : 20
					},
					quality : {
						required : true
					},
					reliability : {
						required : true
					},
					overall : {
						required : true
					}
				},
	
				// Messages for form validation
				messages : {
					name : {
						required : 'Please enter your name'
					},
					email : {
						required : 'Please enter your email address',
						email : '<i class="fa fa-warning"></i><strong>Please enter a VALID email addres</strong>'
					},
					review : {
						required : 'Please enter your review'
					},
					quality : {
						required : 'Please rate quality of the product'
					},
					reliability : {
						required : 'Please rate reliability of the product'
					},
					overall : {
						required : 'Please rate the product'
					}
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
			
			var $commentForm = $("#comment-form").validate({
				// Rules for form validation
				rules : {
					name : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					url : {
						url : true
					},
					comment : {
						required : true
					}
				},
	
				// Messages for form validation
				messages : {
					name : {
						required : 'Enter your name',
					},
					email : {
						required : 'Enter your email address',
						email : 'Enter a VALID email'
					},
					url : {
						email : 'Enter a VALID url'
					},
					comment : {
						required : 'Please enter your comment'
					}
				},
	
				// Ajax form submition
				submitHandler : function(form) {
					$(form).ajaxSubmit({
						success : function() {
							$("#comment-form").addClass('submited');
						}
					});
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	
			var $contactForm = $("#contact-form").validate({
				// Rules for form validation
				rules : {
					name : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					message : {
						required : true,
						minlength : 10
					}
				},
	
				// Messages for form validation
				messages : {
					name : {
						required : 'Please enter your name',
					},
					email : {
						required : 'Please enter your email address',
						email : 'Please enter a VALID email address'
					},
					message : {
						required : 'Please enter your message'
					}
				},
	
				// Ajax form submition
				submitHandler : function(form) {
					$(form).ajaxSubmit({
						success : function() {
							$("#contact-form").addClass('submited');
						}
					});
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	
			var $loginForm = $("#login-form").validate({
				// Rules for form validation
				rules : {
					email : {
						required : true,
						email : true
					},
					password : {
						required : true,
						minlength : 3,
						maxlength : 20
					}
				},
	
				// Messages for form validation
				messages : {
					email : {
						required : 'Please enter your email address',
						email : 'Please enter a VALID email address'
					},
					password : {
						required : 'Please enter your password'
					}
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	
			var $orderForm = $("#order-form").validate({
				// Rules for form validation
				rules : {
					name : {
						required : true
					},
					email : {
						required : true,
						email : true
					},
					phone : {
						required : true
					},
					interested : {
						required : true
					},
					budget : {
						required : true
					}
				},
	
				// Messages for form validation
				messages : {
					name : {
						required : 'Please enter your name'
					},
					email : {
						required : 'Please enter your email address',
						email : 'Please enter a VALID email address'
					},
					phone : {
						required : 'Please enter your phone number'
					},
					interested : {
						required : 'Please select interested service'
					},
					budget : {
						required : 'Please select your budget'
					}
				},
	
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	
			// START AND FINISH DATE
			$('#startdate').datepicker({
				dateFormat : 'dd.mm.yy',
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				onSelect : function(selectedDate) {
					$('#finishdate').datepicker('option', 'minDate', selectedDate);
				}
			});
			
			$('#finishdate').datepicker({
				dateFormat : 'dd.mm.yy',
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>',
				onSelect : function(selectedDate) {
					$('#startdate').datepicker('option', 'maxDate', selectedDate);
				}
			});


		
		})

		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
				_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script>

	</body>

</html>
