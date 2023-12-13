<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Custom CSS styles */
        body {
            background-color: aquamarine;
        }
        .course-card {
            margin-bottom: 20px;
        }
        /* Style for the carousel */
        .carousel-item {
            height: 500px; 
            position: relative;
        }
        .carousel-caption {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            padding: 20px;
        }
        /* Add linear gradient overlay */
        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0) 100%);
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div id="carouselExample" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExample" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExample" data-slide-to="1"></li>
            <li data-target="#carouselExample" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./images/Pic1.png" class="d-block w-100" alt="Budget Planner Slide 1">
                <div class="gradient-overlay"></div>  
                <div class="carousel-caption">
                    <h3>Welcome to Online Budget Planner</h3>
                    <p>Plan and manage your finances with ease using our intuitive online budgeting tools.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/Pic2.jpg" class="d-block w-100" alt="Budget Planner Slide 2">
                <div class="gradient-overlay"></div>  
                <div class="carousel-caption">
                    <h3>Take Control of Your Financial Journey</h3>
                    <p>Explore features designed to empower you on your path to financial well-being.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/Pic3.jpg" class="d-block w-100" alt="Budget Planner Slide 3">
                <div class="gradient-overlay"></div>  
                <div class="carousel-caption">
                    <h3>Achieve Your Financial Goals with Ease</h3>
                    <p>Make every penny count and turn your financial dreams into reality.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container mt-5">
        <h2>Contact Us</h2>
        <p>If you have any questions or need assistance, feel free to contact us using the form below.</p>

        <!-- Contact Form -->
        <form>
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Additional Information -->
        <div class="mt-5">
            <h2>Why Choose Our Budget Planner?</h2>
            <p>Our online budget planner offers:</p>
            <ul>
                <li>Intuitive and user-friendly interface</li>
                <li>Powerful budgeting tools</li>
                <li>Personalized financial insights</li>
                <li>Secure and reliable platform</li>
            </ul>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-light">
        <div class="container text-center">
            <p>&copy; 2023 Budget Planner. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
