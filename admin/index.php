<?php $pageTitle = "Dashboard";
include('header-admin.php');
include('sidebar-admin.php');
?>


<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
  <div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

      <!--  BEGIN BREADCRUMBS  -->
      <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
          <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
              </svg>
            </a>
            <div class="d-flex breadcrumb-content">
              <div class="page-header">

                <div class="page-title">
                </div>

                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                  </ol>
                </nav>

              </div>
            </div>
            <ul class="navbar-nav flex-row ms-auto breadcrumb-action-dropdown">
              <li class="nav-item more-dropdown">
                <div class="dropdown  custom-dropdown-icon">
                  <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Settings</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down custom-dropdown-arrow">
                      <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">

                    <a class="dropdown-item" data-value="Settings" data-icon="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; class=&quot;feather feather-settings&quot;><circle cx=&quot;12&quot; cy=&quot;12&quot; r=&quot;3&quot;></circle><path d=&quot;M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z&quot;></path></svg>" href="javascript:void(0);">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                      </svg> Settings
                    </a>

                    <a class="dropdown-item" data-value="Mail" data-icon="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; class=&quot;feather feather-mail&quot;><path d=&quot;M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z&quot;></path><polyline points=&quot;22,6 12,13 2,6&quot;></polyline></svg>" href="javascript:void(0);">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                      </svg> Mail
                    </a>

                    <a class="dropdown-item" data-value="Print" data-icon="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; class=&quot;feather feather-printer&quot;><polyline points=&quot;6 9 6 2 18 2 18 9&quot;></polyline><path d=&quot;M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2&quot;></path><rect x=&quot;6&quot; y=&quot;14&quot; width=&quot;12&quot; height=&quot;8&quot;></rect></svg>" href="javascript:void(0);">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                      </svg> Print
                    </a>

                    <a class="dropdown-item" data-value="Download" data-icon="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; class=&quot;feather feather-download&quot;><path d=&quot;M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4&quot;></path><polyline points=&quot;7 10 12 15 17 10&quot;></polyline><line x1=&quot;12&quot; y1=&quot;15&quot; x2=&quot;12&quot; y2=&quot;3&quot;></line></svg>" href="javascript:void(0);">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                      </svg> Download
                    </a>

                    <a class="dropdown-item" data-value="Share" data-icon="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;24&quot; height=&quot;24&quot; viewBox=&quot;0 0 24 24&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; class=&quot;feather feather-share-2&quot;><circle cx=&quot;18&quot; cy=&quot;5&quot; r=&quot;3&quot;></circle><circle cx=&quot;6&quot; cy=&quot;12&quot; r=&quot;3&quot;></circle><circle cx=&quot;18&quot; cy=&quot;19&quot; r=&quot;3&quot;></circle><line x1=&quot;8.59&quot; y1=&quot;13.51&quot; x2=&quot;15.42&quot; y2=&quot;17.49&quot;></line><line x1=&quot;15.41&quot; y1=&quot;6.51&quot; x2=&quot;8.59&quot; y2=&quot;10.49&quot;></line></svg>" href="javascript:void(0);">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                      </svg> Share
                    </a>

                  </div>

                </div>
              </li>
            </ul>
          </header>
        </div>
      </div>
      <!--  END BREADCRUMBS  -->

      <div class="row layout-top-spacing">

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-six">
            <div class="widget-heading">
              <h6 class="">Statistics</h6>
              <div class="task-action">
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="statistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                      <circle cx="12" cy="12" r="1"></circle>
                      <circle cx="19" cy="12" r="1"></circle>
                      <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                  </a>

                  <div class="dropdown-menu left" aria-labelledby="statistics" style="will-change: transform;">
                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                    <a class="dropdown-item" href="javascript:void(0);">Download</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="w-chart">
              <div class="w-chart-section">
                <div class="w-detail">
                  <p class="w-title">Total Visits</p>
                  <p class="w-stats">423,964</p>
                </div>
                <div class="w-chart-render-one">
                  <div id="total-users"></div>
                </div>
              </div>

              <div class="w-chart-section">
                <div class="w-detail">
                  <p class="w-title">Paid Visits</p>
                  <p class="w-stats">7,929</p>
                </div>
                <div class="w-chart-render-one">
                  <div id="paid-visits"></div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-card-four">
            <div class="widget-content">
              <div class="w-header">
                <div class="w-info">
                  <h6 class="value">Expenses</h6>
                </div>
                <div class="task-action">
                  <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="expenses" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                      </svg>
                    </a>

                    <div class="dropdown-menu left" aria-labelledby="expenses" style="will-change: transform;">
                      <a class="dropdown-item" href="javascript:void(0);">This Week</a>
                      <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
                      <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="w-content">

                <div class="w-info">
                  <p class="value">$ 45,141 <span>this week</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                      <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                      <polyline points="17 6 23 6 23 12"></polyline>
                    </svg></p>
                </div>

              </div>

              <div class="w-progress-stats">
                <div class="progress">
                  <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="">
                  <div class="w-icon">
                    <p>57%</p>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-card-five">
            <div class="widget-content">
              <div class="account-box">

                <div class="info-box">
                  <div class="icon">
                    <span>
                      <img src="../src/assets/img/money-bag.png" alt="money-bag">
                    </span>
                  </div>

                  <div class="balance-info">
                    <h6>Total Balance</h6>
                    <p>$41,741.42</p>
                  </div>
                </div>

                <div class="card-bottom-section">
                  <div><span class="badge badge-light-success">+ 13.6% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                      </svg></span></div>
                  <a href="javascript:void(0);" class="">View Report</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-chart-three">
            <div class="widget-heading">
              <div class="">
                <h5 class="">Unique Visitors</h5>
              </div>

              <div class="task-action">
                <div class="dropdown ">
                  <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                      <circle cx="12" cy="12" r="1"></circle>
                      <circle cx="19" cy="12" r="1"></circle>
                      <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                  </a>

                  <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                    <a class="dropdown-item" href="javascript:void(0);">Update</a>
                    <a class="dropdown-item" href="javascript:void(0);">Download</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="widget-content">
              <div id="uniqueVisits"></div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-activity-five">

            <div class="widget-heading">
              <h5 class="">Activity Log</h5>

              <div class="task-action">
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="activitylog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                      <circle cx="12" cy="12" r="1"></circle>
                      <circle cx="19" cy="12" r="1"></circle>
                      <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                  </a>

                  <div class="dropdown-menu left" aria-labelledby="activitylog" style="will-change: transform;">
                    <a class="dropdown-item" href="javascript:void(0);">View All</a>
                    <a class="dropdown-item" href="javascript:void(0);">Mark as Read</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="widget-content">

              <div class="w-shadow-top"></div>

              <div class="mt-container mx-auto">
                <div class="timeline-line">

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                          <line x1="12" y1="5" x2="12" y2="19"></line>
                          <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>New project created : <a href="javscript:void(0);"><span>[Cork Admin]</span></a></h5>
                      </div>
                      <p>07 May, 2022</p>
                    </div>
                  </div>

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                          <polyline points="22,6 12,13 2,6"></polyline>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>Mail sent to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></h5>
                      </div>
                      <p>06 May, 2022</p>
                    </div>
                  </div>

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>Server Logs Updated</h5>
                      </div>
                      <p>01 May, 2022</p>
                    </div>
                  </div>

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>Task Completed : <a href="javscript:void(0);"><span>[Backup Files EOD]</span></a></h5>
                      </div>
                      <p>30 Apr, 2022</p>
                    </div>
                  </div>

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                          <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                          <polyline points="13 2 13 9 20 9"></polyline>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>Documents Submitted from <a href="javascript:void(0);">Sara</a></h5>
                        <span class=""></span>
                      </div>
                      <p>25 Apr, 2022</p>
                    </div>
                  </div>

                  <div class="item-timeline timeline-new">
                    <div class="t-dot">
                      <div class="t-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server">
                          <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                          <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                          <line x1="6" y1="6" x2="6" y2="6"></line>
                          <line x1="6" y1="18" x2="6" y2="18"></line>
                        </svg></div>
                    </div>
                    <div class="t-content">
                      <div class="t-uppercontent">
                        <h5>Server rebooted successfully</h5>
                        <span class=""></span>
                      </div>
                      <p>10 Apr, 2022</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="w-shadow-bottom"></div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget-four">
            <div class="widget-heading">
              <h5 class="">Visitors by Browser</h5>
            </div>
            <div class="widget-content">
              <div class="vistorsBrowser">
                <div class="browser-list">
                  <div class="w-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chrome">
                      <circle cx="12" cy="12" r="10"></circle>
                      <circle cx="12" cy="12" r="4"></circle>
                      <line x1="21.17" y1="8" x2="12" y2="8"></line>
                      <line x1="3.95" y1="6.06" x2="8.54" y2="14"></line>
                      <line x1="10.88" y1="21.94" x2="15.46" y2="14"></line>
                    </svg>
                  </div>
                  <div class="w-browser-details">
                    <div class="w-browser-info">
                      <h6>Chrome</h6>
                      <p class="browser-count">65%</p>
                    </div>
                    <div class="w-browser-stats">
                      <div class="progress">
                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="browser-list">
                  <div class="w-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-compass">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                    </svg>
                  </div>
                  <div class="w-browser-details">

                    <div class="w-browser-info">
                      <h6>Safari</h6>
                      <p class="browser-count">25%</p>
                    </div>

                    <div class="w-browser-stats">
                      <div class="progress">
                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 35%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>

                  </div>

                </div>

                <div class="browser-list">
                  <div class="w-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="2" y1="12" x2="22" y2="12"></line>
                      <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                  </div>
                  <div class="w-browser-details">

                    <div class="w-browser-info">
                      <h6>Others</h6>
                      <p class="browser-count">15%</p>
                    </div>

                    <div class="w-browser-stats">
                      <div class="progress">
                        <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>

                  </div>

                </div>

              </div>

            </div>
          </div>
        </div>

        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="row widget-statistic">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
              <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                  <div class="w-title">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                      </svg>
                    </div>
                    <div class="">
                      <p class="w-value">31.6K</p>
                      <h5 class="">Followers</h5>
                    </div>
                  </div>
                </div>
                <div class="widget-content">
                  <div class="w-chart">
                    <div id="hybrid_followers"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
              <div class="widget widget-one_hybrid widget-referral">
                <div class="widget-heading">
                  <div class="w-title">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                      </svg>
                    </div>
                    <div class="">
                      <p class="w-value">1,900</p>
                      <h5 class="">Referral</h5>
                    </div>
                  </div>
                </div>
                <div class="widget-content">
                  <div class="w-chart">
                    <div id="hybrid_followers1"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
              <div class="widget widget-one_hybrid widget-engagement">
                <div class="widget-heading">
                  <div class="w-title">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                      </svg>
                    </div>
                    <div class="">
                      <p class="w-value">18.2%</p>
                      <h5 class="">Engagement</h5>
                    </div>
                  </div>
                </div>
                <div class="widget-content">
                  <div class="w-chart">
                    <div id="hybrid_followers3"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-five">

            <div class="widget-heading">

              <a href="javascript:void(0)" class="task-info">

                <div class="usr-avatar">
                  <span>FD</span>
                </div>

                <div class="w-title">

                  <h5>Figma Design</h5>
                  <span>Design Project</span>

                </div>

              </a>

              <div class="task-action">
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                      <circle cx="12" cy="12" r="1"></circle>
                      <circle cx="19" cy="12" r="1"></circle>
                      <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                  </a>

                  <div class="dropdown-menu left" aria-labelledby="pendingTask" style="will-change: transform;">
                    <a class="dropdown-item" href="javascript:void(0);">View Project</a>
                    <a class="dropdown-item" href="javascript:void(0);">Edit Project</a>
                    <a class="dropdown-item" href="javascript:void(0);">Mark as Done</a>
                  </div>
                </div>
              </div>

            </div>


            <div class="widget-content">

              <p>Doloribus nisi vel suscipit modi, optio ex repudiandae voluptatibus officiis commodi.</p>

              <div class="progress-data">

                <div class="progress-info">
                  <div class="task-count"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square">
                      <polyline points="9 11 12 14 22 4"></polyline>
                      <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <p>5 Tasks</p>
                  </div>
                  <div class="progress-stats">
                    <p>86.2%</p>
                  </div>
                </div>

                <div class="progress">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

              </div>

              <div class="meta-info">

                <div class="due-time">
                  <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                      <circle cx="12" cy="12" r="10"></circle>
                      <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> 3 Days Left</p>
                </div>


                <div class="avatar--group">

                  <div class="avatar translateY-axis more-group">
                    <span class="avatar-title">+6</span>
                  </div>
                  <div class="avatar translateY-axis">
                    <img alt="avatar" src="../src/assets/img/profile-8.jpeg" />
                  </div>
                  <div class="avatar translateY-axis">
                    <img alt="avatar" src="../src/assets/img/profile-12.jpeg" />
                  </div>
                  <div class="avatar translateY-axis">
                    <img alt="avatar" src="../src/assets/img/profile-19.jpeg" />
                  </div>

                </div>

              </div>


            </div>

          </div>

        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-card-one">
            <div class="widget-content">

              <div class="media">
                <div class="w-img">
                  <img src="../src/assets/img/profile-19.jpeg" alt="avatar">
                </div>
                <div class="media-body">
                  <h6>Jimmy Turner</h6>
                  <p class="meta-date-time">Monday, May 18</p>
                </div>
              </div>

              <p>"Duis aute irure dolor" in reprehenderit in voluptate velit esse cillum "dolore eu fugiat" nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>

              <div class="w-action">
                <div class="card-like">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                  </svg>
                  <span>551 Likes</span>
                </div>

                <div class="read-more">
                  <a href="javascript:void(0);">Read More <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right">
                      <polyline points="13 17 18 12 13 7"></polyline>
                      <polyline points="6 17 11 12 6 7"></polyline>
                    </svg></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-card-two">
            <div class="widget-content">

              <div class="media">
                <div class="w-img">
                  <img src="../src/assets/img/g-8.png" alt="avatar">
                </div>
                <div class="media-body">
                  <h6>Dev Summit - New York</h6>
                  <p class="meta-date-time">Bronx, NY</p>
                </div>
              </div>

              <div class="card-bottom-section">
                <h5>4 Members Going</h5>
                <div class="img-group">
                  <img src="../src/assets/img/profile-19.jpeg" alt="avatar">
                  <img src="../src/assets/img/profile-6.jpeg" alt="avatar">
                  <img src="../src/assets/img/profile-8.jpeg" alt="avatar">
                  <img src="../src/assets/img/profile-3.jpeg" alt="avatar">
                </div>
                <a href="javascript:void(0);" class="btn">View Details</a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
  
<?php include('footer-admin.php'); ?>