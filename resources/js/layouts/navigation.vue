<script setup>
    import{HomeIcon, KeyIcon, UserIcon, DocumentDuplicateIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
    const masterfileDrop = ref(false);
    const potransDrop = ref(false);
    const userDrop = ref(false);
    const notif = ref(false);

    const menu = ref(false);
</script>
<template>
    <div class="container-scroller">
        
    <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
                <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
                <!-- <a class="navbar-brand brand-logo" href="index.html">\PURCHASING</a>
                <a class="navbar-brand brand-logo-mini" href="index.html"><img src="" alt="logo"/>P</a> -->
                <div class="text-2xl uppercase font-bold text-orange-500 w-full text-center">
                    Purchasing
                </div>
                <button class="navbar-toggler navbar-toggler align-self-center"  @click="toggleSidebar" type="button" data-toggle="minimize">
                    <span class="mdi mdi-sort-variant"></span>
                </button>
                </div>  
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav mr-lg-4 w-100">
                <li class="nav-item nav-search d-none d-lg-block w-100">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                        <i class="mdi mdi-magnify"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search now" aria-label="search" aria-describedby="search">
                    </div>
                </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown mr-1">
                    <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-message-text mx-0"></i>
                    <!-- <span class="count"></span> -->
                    </a>
                    <!-- <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="messageDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                            <img  alt="image" class="profile-pic">
                        </div>
                        <div class="item-content flex-grow">
                        <h6 class="ellipsis font-weight-normal">David Grey
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                            The meeting is cancelled
                        </p>
                        </div>
                    </a>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                            <img  alt="image" class="profile-pic">
                        </div>
                        <div class="item-content flex-grow">
                        <h6 class="ellipsis font-weight-normal">Tim Cook
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                            New product launch
                        </p>
                        </div>
                    </a>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                            <img  alt="image" class="profile-pic">
                        </div>
                        <div class="item-content flex-grow">
                        <h6 class="ellipsis font-weight-normal"> Johnson
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                            Upcoming board meeting
                        </p>
                        </div>
                    </a>
                    </div> -->
                </li>
                <li class="nav-item dropdown mr-4">
                    <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center notification-dropdown" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell mx-0"></i>
                    <!-- <span class="count"></span> -->
                    </a>
                    <!-- <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                        <div class="item-icon bg-success">
                            <i class="mdi mdi-information mx-0"></i>
                        </div>
                        </div>
                        <div class="item-content">
                        <h6 class="font-weight-normal">Application Error</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                            Just now
                        </p>
                        </div>
                    </a>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                        <div class="item-icon bg-warning">
                            <i class="mdi mdi-settings mx-0"></i>
                        </div>
                        </div>
                        <div class="item-content">
                        <h6 class="font-weight-normal">Settings</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                            Private message
                        </p>
                        </div>
                    </a>
                    <a class="dropdown-item">
                        <div class="item-thumbnail">
                        <div class="item-icon bg-info">
                            <i class="mdi mdi-account-box mx-0"></i>
                        </div>
                        </div>
                        <div class="item-content">
                        <h6 class="font-weight-normal">New user registration</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                            2 days ago
                        </p>
                        </div>
                    </a>
                    </div> -->
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle !flex" href="#" data-toggle="dropdown" id="profileDropdown" @click="userDrop = !userDrop">
                      
                            <span>
                                <UserIcon  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></UserIcon>
                            </span>
                            <span class="nav-profile-name">Louis Barnett</span>
                    </a>
                    <Transition
						enter-active-class="transition ease-out duration-200"
						enter-from-class="opacity-0"
						enter-to-class="opacity-100 "
						leave-active-class="transition ease-in duration-200"
						leave-from-class="opacity-100 "
						leave-to-class="opacity-0"
					>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown"  v-show="userDrop">
                        <a class="dropdown-item">
                            <i class="mdi mdi-settings text-primary"></i>
                            Settings
                        </a>
                        <a class="dropdown-item">
                            <i class="mdi mdi-logout text-primary"></i>
                            Logout
                        </a>
                    </div>
                    </Transition>
                </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas " id="sidebar">
                <ul class="nav ">
                    <li class="nav-item">
                        <a class="nav-link !text-gray-600" href="/dashboard">
                        <i class="mdi mdi-home menu-icon !text-gray-600">
                            <HomeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></HomeIcon>
                        </i>
                        <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link !text-gray-600 cursor-pointer" @click="masterfileDrop = !masterfileDrop" >
                            <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                <KeyIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></KeyIcon>
                            </i>
                                <span class="menu-title">Masterfile</span>
                            <!-- <i class="menu-arrow"></i> -->
                        </a>
                        <Transition
                            enter-active-class="transition ease-out duration-250"
                            enter-from-class="opacity-0 h-1/2"
                            enter-to-class="opacity-100 h-full"
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100 h-full"
                            leave-to-class="opacity-0 h-1/2"
                        >
                            <div class=""  v-show="masterfileDrop">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/department">Department</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/company">Company</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/vendor">Vendors</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/employee">Employee</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/unit">Unit</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/project">Project/Activity</a></li>
                                </ul>
                            </div>
                        </Transition>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link !text-gray-600 cursor-pointer" @click="potransDrop = !potransDrop" >
                            <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                <DocumentDuplicateIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentDuplicateIcon>
                            </i>
                                <span class="menu-title">PO Transactions</span>
                            <!-- <i class="menu-arrow"></i> -->
                        </a>
                        <Transition
                            enter-active-class="transition ease-out duration-250"
                            enter-from-class="opacity-0 h-1/2"
                            enter-to-class="opacity-100 h-full"
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100 h-full"
                            leave-to-class="opacity-0 h-1/2"
                        >
                            <div class=""  v-show="potransDrop">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item list-none"> <a class="nav-link" href="/pur_req">Purchase Request</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/pur_quote">Request For Quotation</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/pur_aoq">Abstract Of Quotation</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/vendor">Purchase Order</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/employee">Request For Disbursement</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/unit">Delivery Receipt</a></li>
                                </ul>
                            </div>
                        </Transition>
                    </li>
                    
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>

</template>