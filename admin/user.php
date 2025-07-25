<?php

$pageTitle = "User";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';

$users = $DB->read("users");

if (isset($_GET['d_id'])) {
    $DB->delete("users", "id", $_GET['d_id']);
    header("Location: user.php");
    exit();
}

?>


<div class="row layout-spacing layout-top-spacing" id="cancel-row">
    <div class="col-lg-12">
        <div class="widget-content searchable-container list">

            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search layout-spacing align-self-center">
                    <form class="form-inline my-2 my-lg-0">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" class="form-control product-search" id="input-search" placeholder="Search Contacts...">
                        </div>
                    </form>
                </div>

                <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center layout-spacing align-self-center">
                    <div class="d-flex justify-content-sm-end justify-content-center">
                        <a href="user-add.php">
                            <svg id="btn-add-contact" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </a>

                        <div class="switch align-self-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list view-list active-view">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3" y2="6"></line>
                                <line x1="3" y1="12" x2="3" y2="12"></line>
                                <line x1="3" y1="18" x2="3" y2="18"></line>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid view-grid">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title add-title" id="addContactModalTitleLabel1">Add Contact</h5>
                                    <h5 class="modal-title edit-title" id="addContactModalTitleLabel2" style="display: none;">Edit Contact</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="add-contact-box">
                                        <div class="add-contact-content">
                                            <form id="addContactModalTitle">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="contact-name">
                                                            <input type="text" id="c-name" class="form-control" placeholder="Name">
                                                            <span class="validation-text"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="contact-email">
                                                            <input type="text" id="c-email" class="form-control" placeholder="Email">
                                                            <span class="validation-text"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="contact-occupation">
                                                            <input type="text" id="c-occupation" class="form-control" placeholder="Occupation">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <div class="contact-phone">
                                                            <input type="text" id="c-phone" class="form-control" placeholder="Phone">
                                                            <span class="validation-text"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="contact-location">
                                                            <input type="text" id="c-location" class="form-control" placeholder="Location">
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-edit" class="float-left btn btn-success">Save</button>

                                    <button class="btn" data-bs-dismiss="modal"> <i class="flaticon-delete-1"></i> Discard</button>

                                    <button id="btn-add" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="searchable-items list">
                <div class="items items-header-section">
                    <div class="item-content">
                        <div class="d-inline-flex">
                            <div class="n-chk align-self-center text-center">
                            </div>
                            <h4>Name</h4>
                        </div>
                        <div class="user-email">
                            <h4>Email</h4>
                        </div>
                        <div class="user-location">
                            <h4 style="margin-left: 0;">Login Type</h4>
                        </div>
                        <div class="user-phone">
                            <h4 style="margin-left: 3px;">Phone</h4>
                        </div>
                        <div class="user-phone">
                            <h4 style="margin-left: 3px;">Is Verified</h4>
                        </div>
                        <div class="user-phone">
                            <h4 style="margin-left: 3px;">Action</h4>
                        </div>

                    </div>
                </div>

                <?php


                if ($users && mysqli_num_rows($users) > 0) {
                    while ($row = mysqli_fetch_assoc($users)) {
                ?>
                        <div class="items">
                            <div class="item-content">
                                <div class="user-profile">
                                    <img src="<?php echo ($row['profile_picture'] == null) ? '../images/profile_picture.jpg' : '../images/profile/' . $row['profile_picture']; ?>" alt="avatar" class="img-fluid" style="max-width: 60px; max-height: 60px;">
                                    <div class="user-meta-info">
                                        <p class="user-name" data-name="Alan Green"><?php echo $row['name']; ?></p>
                                        <p class="user-work" data-occupation="Web Developer"><?php echo $row['role']; ?></p>
                                    </div>
                                </div>
                                <div class="user-email">
                                    <p class="info-title">Email: </p>
                                    <p class="usr-email-addr" data-email="alan@mail.com"><?php echo $row['email']; ?></p>
                                </div>
                                <div class="user-location">
                                    <p class="info-title">Login Type</p>
                                    <p class="usr-location" data-location="Boston, USA"><?php echo $row['auth_provider']; ?></p>
                                </div>
                                <div class="user-phone">
                                    <p class="info-title">Phone: </p>
                                    <p class="usr-ph-no" data-phone="+1 (070) 123-4567"><?php echo $row['mobile_no']; ?></p>
                                </div>
                                <div class="user-phone">
                                    <p class="info-title">Is verified: </p>
                                    <p class="usr-ph-no" data-phone="+1 (070) 123-4567"><?php echo $row['is_verified'] ? 'Yes' : 'No'; ?></p>
                                </div>
                                <div class="action-btn">
                                    <a href="user-add.php?u_id=<?php echo $row['id']; ?>" onclick="event.stopPropagation();">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </a>
                                    <a href="user.php?d_id=<?php echo $row['id']; ?>" onclick="event.stopPropagation(); if(confirm('Are you sure you want to delete this user?')) { window.location.href=this.href; } return false;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-minus delete">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="23" y1="11" x2="17" y2="11"></line>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center mt-3'>No users found.</p>";
                }
                ?>
            </div>

        </div>
    </div>
</div>

<?php require_once './include/footer-admin.php'; ?>