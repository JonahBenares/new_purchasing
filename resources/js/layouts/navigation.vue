<script setup>
    import{HomeIcon, KeyIcon, UserIcon, DocumentTextIcon, RectangleGroupIcon, Square3Stack3DIcon, DocumentDuplicateIcon, TruckIcon, BanknotesIcon , Squares2X2Icon, TableCellsIcon, NewspaperIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref , onMounted} from "vue"
    import { useRouter } from "vue-router"
    const router = useRouter();
    onMounted(async () => {
		getDashboard()
	})
    let credentials=ref([])
    const userDrop = ref(false);
    const templatesDrop = ref(false);
    const notif = ref(false);
    
    const menu = ref(false);
    const masterfileDrop = ref(false);
    const prDrop = ref(false);
    const rfqDrop = ref(false);
    const aoqDrop = ref(false);
    const poDrop = ref(false);
    const drDrop = ref(false);
    const rfdDrop = ref(false);

    const jobDrop = ref(false);
    const jobRfqDrop = ref(false);
    const joiDrop = ref(false);
    const jobDrDrop = ref(false);
    const jobRfdDrop = ref(false);
    const jobJOAOQDrop = ref(false);

    const jobReportDrop = ref(false);
    const weeklyPORecomDrop = ref(false);
    const weeklyJORRecomDrop = ref(false);
    const monthlyPORecomDrop = ref(false);
    const monthlyJORRecomDrop = ref(false);

	const hideDrop = ref(true)
	const openMaster = () => {
		masterfileDrop.value = !masterfileDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value        
		aoqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value 
        monthlyJORRecomDrop.value = !hideDrop.value
	}
    const openPR = () => {
		prDrop.value = !prDrop.value
        masterfileDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
		aoqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value
        weeklyJORRecomDrop.value = !hideDrop.value 
        monthlyPORecomDrop.value = !hideDrop.value 
        monthlyJORRecomDrop.value = !hideDrop.value
	}
    const openRFQ = () => {
		rfqDrop.value = !rfqDrop.value
		aoqDrop.value = !hideDrop.value
        prDrop.value = !hideDrop.value
        masterfileDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openAOQ = () => {
		aoqDrop.value = !aoqDrop.value
		rfqDrop.value = !hideDrop.value
        prDrop.value = !hideDrop.value
        masterfileDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openPO = () => {
        poDrop.value = !poDrop.value
        aoqDrop.value = !hideDrop.value
		rfqDrop.value = !hideDrop.value
        prDrop.value = !hideDrop.value
        masterfileDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value 
        monthlyJORRecomDrop.value = !hideDrop.value
	}
    const openDR = () => {
        drDrop.value = !drDrop.value
        aoqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
		rfqDrop.value = !hideDrop.value
        prDrop.value = !hideDrop.value
        masterfileDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openRFD = () => {
        rfdDrop.value = !rfdDrop.value
        aoqDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
		rfqDrop.value = !hideDrop.value
        prDrop.value = !hideDrop.value
        masterfileDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openJobReq = () => {
        jobDrop.value = !jobDrop.value
        aoqDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openJobQuote = () => {
        jobRfqDrop.value = !jobRfqDrop.value
        aoqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
        
	}
    const openJOAOQ = () => {
        jobJOAOQDrop.value = !jobJOAOQDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
        aoqDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
        
	}
    const openJobIssue = () => {
        joiDrop.value = !joiDrop.value
        aoqDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openJobRFD = () => {
        jobRfdDrop.value = !jobRfdDrop.value
        aoqDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const openJobDR = () => {
        jobDrDrop.value = !jobDrDrop.value
        aoqDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value 
        monthlyJORRecomDrop.value = !hideDrop.value
	}
    const openJobReport = () => {
        jobReportDrop.value = !jobReportDrop.value 
        aoqDrop.value = !hideDrop.value
        jobDrDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        weeklyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const weeklyPORecomOpen = () => {
        weeklyPORecomDrop.value = !weeklyPORecomDrop.value 
        aoqDrop.value = !hideDrop.value
        jobReportDrop.value = !hideDrop.value 
        jobDrDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        weeklyJORRecomDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}
    const weeklyJORRecomOpen = () => {
        weeklyJORRecomDrop.value = !weeklyJORRecomDrop.value
        aoqDrop.value = !hideDrop.value
        weeklyPORecomDrop.value = !hideDrop.value 
        jobReportDrop.value = !hideDrop.value 
        jobDrDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value 
	}

    const monthlyPORecomOpen = () => {
        monthlyPORecomDrop.value = !monthlyPORecomDrop.value 
        aoqDrop.value = !hideDrop.value
        weeklyJORRecomDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        jobReportDrop.value = !hideDrop.value 
        jobDrDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
        monthlyJORRecomDrop.value = !hideDrop.value
	}

    const monthlyJORRecomOpen = () => {
        monthlyJORRecomDrop.value = !monthlyJORRecomDrop.value 
        aoqDrop.value = !hideDrop.value
        monthlyPORecomDrop.value = !hideDrop.value 
        weeklyJORRecomDrop.value = !hideDrop.value 
        weeklyPORecomDrop.value = !hideDrop.value 
        jobReportDrop.value = !hideDrop.value 
        jobDrDrop.value = !hideDrop.value
        jobRfdDrop.value = !hideDrop.value
        joiDrop.value = !hideDrop.value
        jobRfqDrop.value = !hideDrop.value
        jobDrop.value = !hideDrop.value
		masterfileDrop.value = !hideDrop.value
		prDrop.value = !hideDrop.value
        rfqDrop.value = !hideDrop.value
        poDrop.value = !hideDrop.value
        drDrop.value = !hideDrop.value
        rfdDrop.value = !hideDrop.value
	}
    
	const closeModal = () => {
		drawer_dr.value = !hideDrop.value
		drawer_rfd.value = !hideDrop.value
		drawer_revise.value = !hideDrop.value
	}
    const logout = () => {
		localStorage.removeItem('token')
		router.push('/')
	}

    const getDashboard = async () => {
		const response = await fetch(`/api/dashboard`);
		credentials.value = await response.json();
		if(!credentials.value.name){
			alert('You have been logged out due to inactivity.')
            localStorage.removeItem('token')
			router.push('/')
		}
	}
</script>
<template>
    
    <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" id="topbar">
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
                <ul class="navbar-nav  w-100">
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
                <li class="nav-item dropdown mr-0 ">
                    <a class="nav-link dropdown-toggle text-base !flex" href="#" data-toggle="dropdown" id="templateDropdown" @click="templatesDrop = !templatesDrop">
                        Download Templates
                    </a>
                    <Transition
						enter-active-class="transition ease-out duration-200"
						enter-from-class="opacity-0"
						enter-to-class="opacity-100 "
						leave-active-class="transition ease-in duration-200"
						leave-from-class="opacity-100 "
						leave-to-class="opacity-0"
					>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" v-show="templatesDrop">
                            <a href="../../storage/upload_format/PurchaseRequestForm.xlsx" class="dropdown-item" download> PR Template </a>
                            <a href="../../storage/upload_format/JORequestForm.xlsx" class="dropdown-item" download> JOR Template </a>
                        </div>
                    </Transition>
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle !flex" href="#" data-toggle="dropdown" id="profileDropdown" @click="userDrop = !userDrop">
                        <span>
                            <UserIcon  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></UserIcon>
                        </span>
                        <span class="nav-profile-name">{{ credentials.name }}</span>
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
                        <a class="dropdown-item" href="/change_password/">Change password</a>
						<div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" @click="logout">
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
                <div class="h-[600px] hover:overflow-y-scroll  overflow-y-hidden  !mb-5">
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
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openMaster()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <KeyIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></KeyIcon>
                                </i>
                                    <span class="menu-title">Masterfile</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                            <div class="!hidden"  :class="{ show:masterfileDrop }">
                                <ul class="nav flex-column sub-menu">
                                    <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                    <li class="nav-item list-none"> <a class="nav-link" href="/department">Department</a></li>
                                    <!-- <li class="nav-item list-none"> <a class="nav-link" href="/company">Company</a></li> -->
                                    <li class="nav-item list-none"> <a class="nav-link" href="/vendor">Vendors</a></li>
                                    <li class="nav-item list-none"> <a class="nav-link" href="/employee">Employee</a></li>
                                </ul>
                            </div>
                            </Transition>
                        </li>
                        <li class="nav-item py-2 text-xs uppercase font-bold !text-gray-600 bg-gray-50 px-4">
                            <span class="menu-title">PO Transactions</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openPR()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Purchase Request</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:prDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_req/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_req">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openRFQ()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <RectangleGroupIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></RectangleGroupIcon>
                                </i>
                                    <span class="menu-title">Request for Quotation</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:rfqDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_quote/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_quote">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openAOQ()" >
                                <i class="mdi mdi-home menu-icon !text-gray-600">
                                    <Square3Stack3DIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></Square3Stack3DIcon>
                                </i>
                                <span class="menu-title">Abstract of Quotation</span>
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:aoqDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_aoq/new">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_aoq">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <!-- aoqDrop.value = !hideDrop.value -->
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openPO()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentDuplicateIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentDuplicateIcon>
                                </i>
                                    <span class="menu-title">Purchase Order</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:poDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_po/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_po">Show List</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/po_direct/0">Direct PO</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/po_repeat">Repeat PO</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openDR()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <TruckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></TruckIcon>
                                </i>
                                    <span class="menu-title">Delivery Receipt</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:drDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_dr/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_dr">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openRFD()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <BanknotesIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></BanknotesIcon>
                                </i>
                                    <span class="menu-title">Request for DISBMT</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:rfdDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_disburse/new">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/pur_disburse">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item py-2 text-xs uppercase font-bold !text-gray-600 bg-gray-50 px-4">
                            <span class="menu-title">JO Transactions</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJobReq()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Job Order Request</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:jobDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_req/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_req">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJobQuote()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <TableCellsIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></TableCellsIcon>
                                </i>
                                    <span class="menu-title">Request for Quotation</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:jobRfqDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_quote/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_quote">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJOAOQ()" >
                                <i class="mdi mdi-home menu-icon !text-gray-600">
                                    <Square3Stack3DIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></Square3Stack3DIcon>
                                </i>
                                <span class="menu-title">Abstract of Quotation</span>
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:jobJOAOQDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_aoq/new">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_aoq">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link !text-gray-600" href="/job_aoq">
                                <i class="mdi mdi-home menu-icon !text-gray-600">
                                    <NewspaperIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></NewspaperIcon >
                                </i>
                                <span class="menu-title">Abstract of Quotation</span>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJobIssue()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentDuplicateIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentDuplicateIcon>
                                </i>
                                    <span class="menu-title">Job Order Issuance</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:joiDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_issue/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_issue">Show List</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_direct">Direct JOI</a></li>
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/po_repeat">Repeat PO</a></li> -->
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJobDR()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <TruckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></TruckIcon>
                                </i>
                                    <span class="menu-title">Delivery Receipt</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:jobDrDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_dr/new/0">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_dr">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="openJobRFD()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <BanknotesIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></BanknotesIcon>
                                </i>
                                    <span class="menu-title">Request for DISBMT</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:jobRfdDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_disburse/new">Add New</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_disburse">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item py-2 text-xs uppercase font-bold !text-gray-600 bg-gray-50 px-4">
                            <span class="menu-title">PO Reports</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="weeklyPORecomOpen()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Weekly Recom</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:weeklyPORecomDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item list-none"><a class="nav-link" href="/pur_weekly_report/new" target="_blank">Encode</a></li>
                                        <li class="nav-item list-none"><a class="nav-link" href="/pur_weekly_report" target="_blank">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="monthlyPORecomOpen()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Monthly Budget</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:monthlyPORecomDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item list-none"><a class="nav-link" href="/pur_monthly_report/new" target="_blank">Encode</a></li>
                                        <li class="nav-item list-none"><a class="nav-link" href="/pur_monthly_report" target="_blank">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item py-2 text-xs uppercase font-bold !text-gray-600 bg-gray-50 px-4">
                            <span class="menu-title">JO Reports</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="weeklyJORRecomOpen()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Weekly Recom</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:weeklyJORRecomDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_weekly_report/new" target="_blank">Encode</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_weekly_report" target="_blank">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link !text-gray-600 cursor-pointer" @click="monthlyJORRecomOpen()" >
                                <i class="mdi mdi-circle-outline menu-icon !text-gray-600">
                                    <DocumentTextIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></DocumentTextIcon>
                                </i>
                                    <span class="menu-title">Monthly Budget</span>
                                <!-- <i class="menu-arrow"></i> -->
                            </a>
                            <Transition
                                enter-active-class="transition ease-out duration-500"
                                enter-from-class="opacity-0 h-1/2"
                                enter-to-class="opacity-100 h-full"
                                leave-active-class="transition ease-in duration-500"
                                leave-from-class="opacity-100 h-full"
                                leave-to-class="opacity-0 h-1/2"
                            >
                                <div class="!hidden"  :class="{ show:monthlyJORRecomDrop }">
                                    <ul class="nav flex-column sub-menu">
                                        <!-- <li class="nav-item list-none"> <a class="nav-link" href="/items">Items</a></li> -->
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_monthly_report/new" target="_blank">Encode</a></li>
                                        <li class="nav-item list-none"> <a class="nav-link" href="/job_monthly_report" target="_blank">Show List</a></li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                    </ul>
                </div>
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