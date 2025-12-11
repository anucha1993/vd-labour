<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - MR. THAWATCHAI KORTMANEE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* Define the primary color for this template (Dark Green/Brown) */
        :root {
            --primary-color: #587978; /* Dark Green/Teal */
            --secondary-color: #98B3B2; /* Muted Light Green */
            --background-gray: #f0f0f0;
        }

        /* Background Shapes */
        .top-left-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 300px;
            height: 150px;
            background-color: var(--background-gray); 
            z-index: 1;
        }
        .bottom-curve-bg {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            height: 100px;
            background-color: var(--background-gray);
            clip-path: ellipse(50% 100% at 50% 100%);
            z-index: 1;
        }

        /* HEADER SECTION */
        .header {
            position: relative;
            padding: 20px 40px 10px 40px;
            text-align: right;
            z-index: 2;
        }
        .header h1, .header h2 {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            line-height: 1.1;
        }
        .header h1 {
            color: var(--primary-color);
            font-size: 3em;
            font-weight: 900;
            text-transform: uppercase;
        }
        .header h2 {
            color: #555;
            font-size: 1.5em;
            font-weight: normal;
            margin-bottom: 20px;
        }
        .header .subtitle-text {
            color: var(--secondary-color);
            font-size: 1.2em;
            font-style: italic;
            margin-top: 10px;
        }
        
        /* PROFILE IMAGE */
        .profile-wrapper {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 3;
        }
        .profile-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--secondary-color);
            border: 5px solid white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* MAIN CONTENT (2 COLUMNS) */
        .main-content {
            display: flex;
            padding-top: 180px;
            position: relative;
            z-index: 2;
        }
        .left-column {
            width: 35%;
            padding: 0 20px 20px 40px;
            box-sizing: border-box;
            color: #333;
        }
        .right-column {
            width: 65%;
            padding: 0 40px 20px 20px;
            box-sizing: border-box;
        }

        /* SECTION TITLES */
        .section-title {
            color: var(--primary-color);
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        /* Contact and Profile sections styling */
        .contact-details {
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .contact-details strong {
            display: block;
            font-size: 1.2em;
            color: #333;
            margin-bottom: 5px;
        }
        .profile-text p {
            margin-top: 0;
            font-size: 0.9em;
        }
        .profile-text ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em;
        }
        .profile-text ul li {
            margin-bottom: 2px;
        }

        /* EXPERIENCE SECTION */
        .experience-title {
            color: var(--primary-color);
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .experience-item {
            margin-bottom: 20px;
            position: relative;
            padding-left: 30px;
        }
        .experience-item h4 {
            margin: 0;
            font-size: 1.1em;
            color: #333;
            font-weight: bold;
        }
        .experience-item p.duration {
            margin: 0 0 5px 0;
            color: #333;
            font-weight: bold;
            font-size: 0.9em;
            position: absolute;
            left: -100px;
            top: 0;
            width: 90px;
            text-align: right;
        }
        .experience-item p.role {
            margin: -5px 0 5px 0;
            font-style: normal;
            font-size: 0.9em;
            color: var(--primary-color); /* Role/Department in Primary Color */
        }
        .experience-item ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-top: 5px;
            font-size: 0.9em;
            color: #333;
        }
        
        /* Styles for the circle markers in the Experience section (Timeline look) */
        .experience-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: 50%;
            border: 2px solid white; 
            box-shadow: 0 0 0 2px var(--primary-color); 
        }
        
        .footer-note {
            position: absolute;
            bottom: 20px;
            left: 40px;
            font-size: 0.8em;
            color: #777;
            z-index: 3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-left-bg"></div>
        <div class="bottom-curve-bg"></div>

        <div class="profile-wrapper">
            <div class="profile-circle">
                <img src="" alt="Profile Picture">
            </div>
        </div>

        <div class="header">
            <p class="subtitle-text" style="font-size:1.5em;">Supermarket</p>
            <h1>MR. THAWATCHAI</h1>
            <h1>KORTMANEE</h1>
        </div>

        <div class="main-content">
            
            <div class="left-column">
                
                <div class="section contact-details">
                    <h3 class="section-title">Contact</h3>
                    <strong style="color: var(--primary-color);">090-908-8786</strong>
                    <p style="margin-top:5px;">
                        157 Moo 2, Nong Wa Subdisttrict, Kumphawapi District, Udon Thani Province 41110, Thailand
                    </p>
                </div>

                <div class="section profile-text">
                    <h3 class="section-title">Profile</h3>
                    <p>
                        I am a hardworking and responsible person with several years of experience in factory and warehouse operations. I have experience in packing products, operating basic machinery, and maintaining a clean and safe working environment.
                    </p>
                    <p>
                        I am a quick learner, able to work well in a team, and always follow safety and quality standards. I am ready to contribute my skills and effort to support the success of your company.
                    </p>
                    
                    <h3 class="section-title" style="margin-top: 10px;">Personal Details</h3>
                    <ul>
                        <li>• Date of Birth: 9 December 1985</li>
                        <li>• AGE: 40 Years</li>
                        <li>• STATUS: Single</li>
                        <li>Hight: 170</li>
                        <li>Weight: 73</li>
                        <li>• Shirt size: XL</li>
                        <li>• Pant size: 32</li>
                        <li>• Shoes size: 41</li>
                        <li>• EMERGENCY CONTACT NAME: Ms. Buarom Kortmanee</li>
                        <li>• TEL: 095-171-3775</li>
                        <li>• TYPE OF CAR: Personal car</li>
                        <li>driver's license</li>
                    </ul>
                </div>
            </div>

            <div class="right-column">
                <h3 class="experience-title">Work Experience</h3>

                <div class="experience-item">
                    <p class="duration">2013 - 2025</p>
                    <h4>7-Eleven Convenience Store</h4>
                    <p class="role">Product arrangement / Product packaging department</p>
                    <ul>
                        <li>Greet and assist customers in a friendly manner.</li>
                        <li>Operate the cash register and handle transactions accurately.</li>
                        <li>Stock shelves, organize products, and check expiration dates.</li>
                        <li>Maintain cleanliness and orderliness of the store.</li>
                        <li>Follow company policies and safety standards.</li>
                        <li>Assist in inventory checking and report shortages or damages.</li>
                    </ul>
                </div>

                <div class="experience-item">
                    <p class="duration">2007 - 2013</p>
                    <h4>Paper Box Manufacturing Factory</h4>
                    <p class="role">Production department</p>
                    <ul>
                        <li>Operated machinery for cutting, folding, and assembling paper boxes.</li>
                        <li>Inspected product quality and removed defective items.</li>
                        <li>Packed and organized finished boxes for shipment.</li>
                        <li>Maintained cleanliness and followed safety regulations.</li>
                        <li>Assisted in material handling and production line support.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-note">
            พี่ต้อม / แนนซี่
        </div>
    </div>
</body>
</html>