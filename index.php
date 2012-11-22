<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sign up for Imperial Hackathon</title>
	<meta name="description" content="">
	<meta name="author" content="Sam Wong sam.wong09@imperial.ac.uk">

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Le styles -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/ichackathon.css" rel="stylesheet">
	<!-- <link rel="stylesheet" href="css/chosen.css" /> -->


	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/jquery.tools.min.js" type="text/javascript"></script>
	<script src="js/ichackathon.js" type="text/javascript"></script>

	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-20974018-5']);
	_gaq.push(['_setDomainName', 'ichackathon.com']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
		</head>

		<body>
			<div class="topbar">
				<div class="fill">
					<div class="container">
						<a class="brand" href="">ICHackathon</a>
						<ul class="nav">
							<li class='show_signup_form'><a href="#signup">Sign up</a></li>
							<li><a href="#objectives">What is this about</a></li>
							<li><a href="#agenda">Agenda</a></li>
							<li><a href="#prizes">Prizes</a></li>
							<li><a href="#tools">Tools</a></li>
							<li><a href="#organisers">Credits</a></li>
							<li class="active"><a href="#sponsors">Sponsors</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="jumbotron masthead" id="overview" style='
			background: url(images/ie_docsoc.png) no-repeat center center; background-size: contain; height:200px;'>
			</div>
			<?php
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				?>
				<div class="container confirmation">
					<div class="hero-unit">
						<?php
						/*	Add data into db and gdoc	*/
						date_default_timezone_set('UTC');
						// Zend library include path
						set_include_path("ZendGdata-1.11.11/library");
						// Helper class
						include_once("Google_Spreadsheet.php");
						try{
							$log = "";

							/*	Prepare array	*/
							$timestamp = date(DATE_ATOM, mktime());
							$row = array
							(
								"timestamp"	=>	$timestamp,
								"teamname"	=>	$_POST["teamname"],
								"leader"	=>	$_POST["member1"],
								"email"	=>	$_POST["member1email"],
								"member2"	=>	$_POST["member2"],
								"member2email"	=>	$_POST["member2email"],
								"member3"	=>	$_POST["member3"],
								"member3email"	=>	$_POST["member3email"],
								"member4"	=>	$_POST["member4"],
								"member4email"	=>	$_POST["member4email"],
								"member5"	=>	$_POST["member5"],
								"member5email"	=>	$_POST["member5email"],
								"comments"	=>	$_POST["comments"]
							);

							$vectorised = array_values($row);
							$log .= "Inserting:" . $vectorised.implode(', ', $vectorised) . "\n";

							/*	First add to db	*/
							try{
								$db=new PDO('sqlite:'.dirname(__FILE__) . '/applicants.db');
							}catch(Exception $e){
								fclose(fopen("applicants.db", "w"));
								$db=new PDO('sqlite:'.dirname(__FILE__) . '/applicants.db');
							}

							$db->exec("CREATE TABLE IF NOT EXISTS applicants (timestamp, teamname, leader, email, member2, member2email, member3, member3email, member4, member4email, member5, member5email, comments)");

							$prepared = $db->prepare('INSERT INTO applicants (timestamp, teamname, leader, email, member2, member2email, member3, member3email, member4, member4email, member5, member5email, comments) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);');

							$prepared->execute($vectorised);

							/* Now add to gdoc	*/ 
							$secret_ingradient = explode("\n", file_get_contents("i_would_appreciate_some_privacy"));
							$u = $secret_ingradient[0];
							$p = $secret_ingradient[1];

							$ss = new Google_Spreadsheet($u,$p);
							$ss->useSpreadsheet("ichackathon applicants");

							// if not setting worksheet, "Sheet1" is assumed
							// $ss->useWorksheet("worksheetName");

							if($ss->addRow($row)){
								$log .= $timestamp . " Form data successfully stored using Google Spreadsheet\n";
							}else{
								$log .= $timestamp . " Error, unable to store spreadsheet data\n";
							} 
							file_put_contents("log", $log, FILE_APPEND | LOCK_EX);
			
							/*	Preparation completes, now take care of presentations	*/
							$teamsize = 1;
							for($i=2; $i<=5; $i++){
								if(	strlen($_POST['member' . $i]) > 0	){
									$teamsize = $i;
								}else{
									break;
								}
							}
							?>
						<div class='span14 offset1'>
							<h1>Congratulations!</h1>
							<h2 class="extraspace">You've signed up as a team of <?php echo $teamsize;?>, one giant step closer to the prize.</h2>
							<p>Please check that these details are correct.</p>
							<p>If not, simply submit a new form, we have neural networks that can work out (obvious) duplicates.</p>

							<table>
								<thead>
									<tr>
										<th><?php echo $_POST['teamname']?></th>
										<th>Name</th>
										<th>email</th>
									</tr>
								</thead>
								<tbody>
									<?php
									for($i=1; $i <= $teamsize; $i++){
									?>
										<tr>
											<td><?php 
												if($i == 1){
													echo "<b>Leader</b>";
												}?></td>
											<td><?php echo $_POST["member" . $i]?></td>
											<td><?php echo $_POST["member" . $i . "email"]?></td>
										</tr>
									<?php	
									}
									?>
								</tbody>
							</table>
							<h4>Notes</h4>
							<p><?php echo $_POST["comments"];?></p>
						</div>
				<?php
				}catch(Exception $e){
					$log .= $e->getMessage(). "\n";
					file_put_contents("error", $log, FILE_APPEND | LOCK_EX);
					file_put_contents("log", $log, FILE_APPEND | LOCK_EX);
					?>
					<h1>Uh oh...!</h1>
					<p>I am Sorry! It didn't work out this time. Why don't you try again? You might get lucky this time. Here's what I could recover, I hope nothing is missing.</p>
					<ul>
					<?php
					foreach($_POST as $key => $value){
						echo "<li>$value</li>\n";
					}
				}
				?>
					</div>
				</div>
				<?php
			}
			?>
		<div class="container">
			<div class='span16'>
			</div>
			<div id="signup" class="hero-unit">  
				<h1 style='text-align: center; margin-bottom:0.3em;'>
					ICHackathon
					<span class="btn info large" id='tldrbtn' style='vertical-align: middle; font-size: 20px;'>tl;dr / Signup &raquo;</span>
				</h1>
				<br>
				<div class="row">
					<div class="span8 offset3" id='tldr_signup_form'>
						<div id="signup_form_html">
							<form id='formElt' method='post'>
								<fieldset>
									<legend>Sign up here</legend>
									<div class="clearfix">
										<label for="teamname">Your Team Name</label>
										<div class="input">
											<input class="xlarge" id="teamname" name="teamname" size="30" type="text" placeholder="X-Men">
										</div>
									</div><!-- /clearfix -->
									<div class="clearfix">
										<label for="member1">Leader</label>
										<div class="input">
											<input class="xlarge" id="member1" name="member1" size="30" type="text" placeholder="Professor Charles Francis Xavier">
										</div>
									</div><!-- /clearfix -->
									<div class="clearfix">
										<label for="member1email">Email</label>
										<div class="input input-append">
											<input class="xlarge placeholderRight" id="member1email" name="member1email" size="20" type="text" placeholder="charles.francis63" style="width: 168px;"><span class="add-on emaildomain">@imperial.ac.uk</span>
										</div>
									</div><!-- /clearfix -->
								</fieldset>
								<fieldset>
									<span id="addmember" class="btn success" value="Add Member" style='float: right; margin-right: 30px;'>Member++</span>
									<span id="removemember" class="btn danger" value="Add Member" style='float:right;'>Min</span>
								</fieldset>
								<fieldset>
									<div class="clearfix">
										<label for="comments">Hacking fields & Additional Comments</label>
										<div class="input">
											<textarea class="xlarge" id="comments" name="comments" rows="3"></textarea>
											<span class="help-block">
												Expected platform of your hack (mobile, webapp, pervasive), want something other than Ubuntu, dietary requirements, etc
											</span>
										</div>
									</div>
								</fieldset>
								<div class="actions" style='clear:both;'>
									<input type="checkbox"> I agree this box is unchecked, and I did not pay attention to the <a href="http://nyan.cat">T&C</a><br><br>
									<input type="submit" class="btn primary" value="That was easy">&nbsp;<button type="reset" class="btn">Clear</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span9">
						<h5>An Imperial Entrepreneurs and DocSoc Joint Production</h5>
						<p class="lead">							
							Your team, your idea, your most productive 48 hours.
						</p>
						<p>
							How are you going to make an impact with a dedicated server and a Raspberry Pi?
						</p>
						<p>
							Show Facebook and Microsoft engineers what you are made of.
						</p>
						<p>
							Food and endless supply Redbull will always be there to fuel you throughout the weekend."
						</p>
						
						<p style='text-align: center;'>
							<span class="btn success large" id='main_signup' style='vertical-align: middle; font-size: 20px; margin-bottom: 20px;'>Signup &raquo;</span>
						</p>
					</div>
					<div class="span5">
						<div class="sponsor_panel title_sponsors">
							<h4>With generous support from:</h4>
							<img src="images/microsoft_in_aspect.png"/>
							<img src="images/facebook_in_aspect.png"/>
							<img src="images/oxfordinstruments_in_aspect.png"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span8 offset3" id='main_signup_form'>
					</div>
				</div>
			</div>
			<!-- End of eye catching block -->
			<section id='objective'>
				<div class="page-header">
					<h1>What are we trying to do <small>and what you will be known for</small></h1>
				</div>
				<p>
					This year's aim is to make Imperial stand out and make other universities jealous. This can be done by improving the teaching/learning process, shortening the feedback cycle, fixing broken systems and many other means. What inconveniences you? What is underutilized? What can you do with a network enabled powerpoint player? Can the two projectors be used for anything other than replicating slides? Think big, opportunities are endless.
				</p>
				<br>
			</section>
			<section id='agenda'>
				<div class="page-header">
					<h1>Agenda <small>Postponed to 19 Jan (TBC)</small></h1>
				</div>
				<table class='zebra-striped'>
					<thead><tr><td style="border-top:0px">Date</td><td style="border-top: 0px"></td></tr></thead>
					<tbody>
						<tr>
							<td>Mon 14 Jan Midnight</td><td>Application Deadline</td>
						</tr>
						<tr>
							<td>Wed 16 Jan</td><td>Selected teams get their server</td>
						</tr>
						<tr>
							<td>Sat 19 Jan</td><td>Hacking begins!</td>
						</tr>
					</tbody>
				</table>
			</section>
			<section id='prizes'>
				<div class="page-header">
					<h1>Prizes <small>Get rich quick</small></h1>
				</div>
				<ul class='prizes_ul'>
					<li>Cash prize</li>
					<li>Referral to Angel Investors</li>
					<li>And surprises during the hackathon (!)</li>
				</ul>
			</section>
			<section id='tools'>
				<div class="page-header">
					<h1>Tools <small>We want you to succeed</small></h1>
				</div>
				<h3>The following tools will be provided to help you:</h3>
				<ul class='tools_ul'>
					<li>A dedicated server to host your projects</li>
					<li>A Raspberry Pi (!)</li>
					<li>Endless beverages</li>
					<li>Mentors from Facebook, Microsoft and (Real, established) Entrepreneurs</li>
					<li>And finally, a post-hackathon marketing campaign</li>
				</ul>
			</section>
			<section id='organisers'>
				<div class="page-header">
					<h1>Credits <small>The awesome people who make this happen</small></h1>
				</div>
				<h2 style="text-align:center">An Imperial Entrepreneurs & DocSoc joint production</h2>
				<h2 style="text-align:center"><u>The A Team:</u></h2>
				<table class="zebra-striped centered_table">
					<thead><tr>
						<th colspan="2">
							<h3><a href="mailto:sam.wong09@imperial.ac.uk">Sam Wong: Director</a></h3>
						</th>
					</tr></thead>
					<tbody>
						<tr>
							<td>
								<h3>Rudy Benfredj</h3>
							</td>
							<td>
								<h3>Andrejs Antjufejevs</h3>
							</td>
						</tr>
						<tr>
							<td>
								<h3>Serge Vasylechko</h3>
							</td>
							<td>
								<h3>Maciek Biskupiak</h3>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
			</section>
			<section id='sponsors'>
				<div class="page-header">
					<h1>Our Sponsors ♥ <small>Thank you</small></h1>
				</div>
				<div class='large_sponsors_logos'>
					<img src="images/microsoft_in_aspect.png"/>
					<img src="images/facebook_in_aspect.png"/>
					<img src="images/oxfordinstruments_in_aspect.png"/>
				</div>
				<p class="pull-right"><a href="#">Back to top</a></p>
				<p>&copy; Imperial Entrepreneurs 2012, by <a href="mailto:sam.wong09@imperial.ac.uk">Sam</a></p>
			</section>
			<footer class="footer">

			</footer>

		</div> <!-- /container -->

	</body>
	</html>
