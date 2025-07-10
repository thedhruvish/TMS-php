<?php $pageTitle = "category create";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>


                        
                    <div class="account-settings-container layout-top-spacing">
    
                        <div class="account-content">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>Settings</h2>
                                </div>
                            </div>
    
                            <div class="tab-content" id="animateLineContent-4">
                                <div class="tab-pane fade show active" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                            <form class="section general-info">
                                                <div class="info">
                                                    <h6 class="">General Information</h6>
                                                    <div class="row">
                                                        <div class="col-lg-11 mx-auto">
                                                            <div class="row">
                                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                                    <div class="form">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="fullName">Full Name</label>
                                                                                    <input type="text" class="form-control mb-3" id="fullName" placeholder="Full Name" value="Jimmy Turner">
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="profession">Profession</label>
                                                                                    <input type="text" class="form-control mb-3" id="profession" placeholder="Designer" value="Web Developer">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="country">Country</label>
                                                                                    <select class="form-select mb-3" id="country">
                                                                                        <option>All Countries</option>
                                                                                        <option selected>United States</option>
                                                                                        <option>India</option>
                                                                                        <option>Japan</option>
                                                                                        <option>China</option>
                                                                                        <option>Brazil</option>
                                                                                        <option>Norway</option>
                                                                                        <option>Canada</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="address">Address</label>
                                                                                    <input type="text" class="form-control mb-3" id="address" placeholder="Address" value="New York" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="location">Location</label>
                                                                                    <input type="text" class="form-control mb-3" id="location" placeholder="Location">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="phone">Phone</label>
                                                                                    <input type="text" class="form-control mb-3" id="phone" placeholder="Write your phone number here" value="+1 (530) 555-12121">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="email">Email</label>
                                                                                    <input type="text" class="form-control mb-3" id="email" placeholder="Write your email here" value="Jimmy@gmail.com">
                                                                                </div>
                                                                            </div>                                    
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="website1">Website</label>
                                                                                    <input type="text" class="form-control mb-3" id="website1" placeholder="Enter URL">
                                                                                </div>
                                                                            </div>
    
                                                                            <div class="col-md-12 mt-1">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" value="" id="customCheck1">
                                                                                    <label class="form-check-label" for="customCheck1">Make this my default address</label>
                                                                                </div>
                                                                            </div>
    
                                                                            <div class="col-md-12 mt-1">
                                                                                <div class="form-group text-end">
                                                                                    <button class="btn btn-secondary">Save</button>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>

                </div>

            </div>


<?php include('./include/footer-admin.php'); ?>
