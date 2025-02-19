<div class="content-wrapper">
	<style>
		.access-denied-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			background: url('img/fairview-hospital-hero.jpg') no-repeat center center / cover;
			text-align: center;
		}

		.access-denied-content {
			background: rgba(255, 255, 255, 0.9);
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
			max-width: 600px;
			width: 100%;
		}

		.access-denied-content h1 {
			font-size: 3rem;
			color: #d9534f;
			margin-bottom: 20px;
		}

		.access-denied-content h3 {
			font-size: 1.5rem;
			color: #333;
			margin-bottom: 15px;
		}

		.access-denied-content p {
			font-size: 0.9rem;
			color: #666;
		}

		.access-denied-content .btn-danger {
			background-color: #d9534f;
			border: none;
			padding: 10px 20px;
			font-size: 1rem;
			border-radius: 5px;
			transition: background-color 0.3s ease;
		}

		.access-denied-content .btn-danger:hover {
			background-color: #c9302c;
		}

		.contact-info a {
			color: #d9534f;
			font-weight: bold;
			text-decoration: none;
		}

		.contact-info a:hover {
			text-decoration: underline;
		}

		@media (max-width: 768px) {
			.access-denied-content {
				padding: 20px;
			}

			.access-denied-content h1 {
				font-size: 2rem;
			}

			.access-denied-content h3 {
				font-size: 1.2rem;
			}
		}
	</style>

	<section class="access-denied-wrapper">
		<div class="access-denied-content">
			<h1><i class="fa fa-ban"></i> Account Disabled</h1>
			<h3>Oops! Something went wrong.</h3>
			<p>You are not allowed to access this resource. Your account has been temporarily or permanently disabled.</p>
			<p>If you believe this is a mistake, please contact the Directorate of On-Farm Water Management (OFWM) for assistance:</p>
			<p class="contact-info">
				<i class="fa fa-phone"></i> Phone: 091-9224307-8<br>
				<i class="fa fa-envelope"></i> Email: <a href="mailto:kpiaipofwm@gmail.com">kpiaipofwm@gmail.com</a>
			</p>
			<a href="<?php echo site_url(ADMIN_DIR . "login/logout"); ?>" class="btn btn-danger">
				<i class="fa fa-chevron-left"></i> Go Back to Home Page
			</a>
		</div>
	</section>

</div>