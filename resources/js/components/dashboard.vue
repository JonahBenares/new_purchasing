<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{PlusIcon, UserIcon, CheckIcon,XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	let form=ref([]);
	let reminderform=ref([]);
	let success=ref('');
	let error=ref('');
	let label=ref('');
	let todo_id=ref('');
	let reminder_id=ref('');
	let error_reminder=ref([]);
	let todo_list=ref([]);
	let reminder_list=ref([]);
	const modalNew = ref(false)
	const modalNewReminder = ref(false)
	const hideModal = ref(true)
	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const dangerAlert_itemcomplete = ref(false)
	const successAlert = ref(false)
	const successAlertComplete = ref(false)
	const hideAlert = ref(true)
	const allSelected=ref(false)
	const allSelectedreminder=ref(false)
	const selected_todo=ref([])
	const selected_reminder=ref([])
	onMounted(async () => {
		todoForm()
		getTodo()
		reminderForm()
		getReminder()
	})
	const openNew = () => {
		modalNew.value = !modalNew.value
	}

	const openNewReminder = () => {
		modalNewReminder.value = !modalNewReminder.value
	}

	const closeModal = () => {
		modalNew.value = !hideModal.value
		modalNewReminder.value = !hideModal.value
		successAlert.value = !hideAlert.value
	}

	const closeAlert = () => {
		selected_todo.value=[]
		selected_reminder.value=[]
		allSelected.value=false
		allSelectedreminder.value=false
		successAlert.value = !hideAlert.value
		successAlertComplete.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert_itemcomplete.value = !hideAlert.value
	}

	const todoForm = async () => {
		let response = await axios.get("/api/create_todo");
		form.value = response.data;
	}

	const getTodo = async () => {
		let response = await axios.get("/api/get_todo");
		todo_list.value=response.data.todo
	}

	const reminderForm = async () => {
		let response = await axios.get("/api/create_reminder");
		reminderform.value = response.data;
	}

	const getReminder = async () => {
		let response = await axios.get("/api/get_reminder");
		reminder_list.value=response.data.reminder
	}

	const onSave = () => {
		const formData= new FormData()
		formData.append('todo_description', form.value.todo_description)
		axios.post("/api/add_todo",formData).then(function () {
			success.value='You have successfully added new things to do!'
			form.value.todo_description=''
			successAlert.value = !successAlert.value
			getTodo()
		}, function (err) {
			error.value = err.response.data.message;
			dangerAlert.value = !dangerAlert.value
			getTodo()
		});
	}

	const onSaveReminder = () => {
		const formData= new FormData()
		formData.append('reminder_due_date', reminderform.value.reminder_due_date)
		formData.append('reminder_desc', reminderform.value.reminder_desc)
		axios.post("/api/add_reminder",formData).then(function () {
			success.value='You have successfully added new reminder!'
			reminderform.value.reminder_due_date=''
			reminderform.value.reminder_desc=''
			successAlert.value = !successAlert.value
			getReminder()
		}, function (err) {
			if (err.response.data.errors.reminder_desc) {
				error_reminder.value.push(err.response.data.errors.reminder_desc[0])
			}
			if (err.response.data.errors.reminder_due_date) {
				error_reminder.value.push(err.response.data.errors.reminder_due_date[0])
			}
			dangerAlert.value = !dangerAlert.value
			getReminder()
		});
	}

	const daysRemaining = (due_date) => {
		const currentDate = new Date();
		const dueDate = new Date(due_date);
		// Calculate the difference in milliseconds
		const diffTime = dueDate - currentDate;
		// Convert milliseconds to days
		return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }

	const checkCompletetodo = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/complete_todo/`+id).then(function () {
				label.value=""
				dangerAlert_item.value = !hideAlert.value
				success.value='Successfully completed to-do!'
				successAlertComplete.value = !successAlertComplete.value
				getTodo()
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			label.value="todo"
			todo_id.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const checkCompletereminder = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/complete_reminder/`+id).then(function () {
				label.value=""
				dangerAlert_item.value = !hideAlert.value
				success.value='Successfully completed reminder!'
				successAlertComplete.value = !successAlertComplete.value
				getReminder()
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			label.value="reminder"
			reminder_id.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const selectAll = (option) => {
		// if (allSelected.value) {
			// console.log(selected_todo.value)
			if(option=='yes'){
				const formData= new FormData()
				formData.append('todo', JSON.stringify(selected_todo.value))
				axios.post(`/api/complete_all_todo`,formData).then(function (response) {
					console.log(response.data)
					label.value=''
					dangerAlert_itemcomplete.value = !hideAlert.value
					selected_todo.value=[]
					allSelected.value=false
					success.value='Successfully completed all to-do!'
					successAlertComplete.value = !successAlertComplete.value
					setTimeout(() => {
						closeAlert()
					}, 2000);
					getTodo()
				}, function (err) {
					error.value = err.response.data.message;
				});
			}else{
				todo_list.value.forEach(function (todo) {
					selected_todo.value.push(todo.id);
				});
				selected_todo.value = selected_todo.value;
				label.value='todo'
				dangerAlert_itemcomplete.value = !dangerAlert_itemcomplete.value
			}
		// }
	}
	const select=() => {
		allSelected.value = false;
	}

	const selectAllreminder = (option) => {
		// if (allSelected.value) {
			// console.log(selected_todo.value)
			if(option=='yes'){
				const formData= new FormData()
				formData.append('reminder', JSON.stringify(selected_reminder.value))
				axios.post(`/api/complete_all_reminder`,formData).then(function (response) {
					console.log(response.data)
					label.value=''
					dangerAlert_itemcomplete.value = !hideAlert.value
					selected_reminder.value=[]
					allSelected.value=false
					success.value='Successfully completed all reminders!'
					successAlertComplete.value = !successAlertComplete.value
					setTimeout(() => {
						closeAlert()
					}, 2000);
					getReminder()
				}, function (err) {
					error.value = err.response.data.message;
				});
			}else{
				reminder_list.value.forEach(function (reminder) {
					selected_reminder.value.push(reminder.id);
				});
				selected_reminder.value = selected_reminder.value;
				label.value='reminder'
				dangerAlert_itemcomplete.value = !dangerAlert_itemcomplete.value
			}
		// }
	}
	const selectreminder=() => {
		allSelectedreminder.value = false;
	}
</script>
<template>
	<navigation>
		<div class="row">
			<div class="col-md-12 grid-margin">
				<div class="d-flex justify-content-between flex-wrap">
					<div class="d-flex align-items-end flex-wrap">
						<div class="mr-md-3 mr-xl-5">
						<h2>Welcome back,</h2>
						<p class="mb-md-0">Your analytics dashboard template.</p>
						</div>
						<div class="d-flex">
						<i class="mdi mdi-home text-muted hover-cursor"></i>
						<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
						<p class="text-primary mb-0 hover-cursor">Analytics</p>
						</div>
					</div>
					<!-- <div class="d-flex justify-content-between align-items-end flex-wrap">
						<button type="button" class="btn btn-light bg-white btn-icon mr-3 d-none d-md-block ">
						<i class="mdi mdi-download text-muted"></i>
						</button>
						<button type="button" class="btn btn-light bg-white btn-icon mr-3 mt-2 mt-xl-0">
						<i class="mdi mdi-clock-outline text-muted"></i>
						</button>
						<button type="button" class="btn btn-light bg-white btn-icon mr-3 mt-2 mt-xl-0">
						<i class="mdi mdi-plus text-muted"></i>
						</button>
						<button class="btn btn-primary mt-2 mt-xl-0">Generate report</button>
					</div> -->
				</div>
			</div>
		</div>
		<!-- <div class="row" id="proBanner">
			<div class="col-md-12 grid-margin">
				<div class="card bg-gradient-primary border-0">
				<div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap">
					<p class="mb-0 text-white font-weight-medium">Get tons of UI components, Plugins, multiple layouts, 20+ sample pages, and more!                  </p>
					<div class="d-flex">
					<a href="https://www.bootstrapdash.com/product/majestic-admin-pro?utm_source=organic&utm_medium=banner&utm_campaign=free-preview" target="_blank" class="btn btn-outline-light mr-2 bg-gradient-danger border-0">Check Pro Version</a>
					<button id="bannerClose" class="btn border-0 p-0 ml-auto">
						<i class="mdi mdi-close text-white"></i>
					</button>
					</div>
				</div>
				</div>
			</div>
		</div> -->
		<!-- <div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body dashboard-tabs p-0">
					<ul class="nav nav-tabs px-4" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false">Sales</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="purchases-tab" data-toggle="tab" href="#purchases" role="tab" aria-controls="purchases" aria-selected="false">Purchases</a>
					</li>
					</ul>
					<div class="tab-content py-0 px-0">
					<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
						<div class="d-flex flex-wrap justify-content-xl-between">
						<div class="d-none d-xl-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-calendar-heart icon-lg mr-3 text-primary"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Start date</small>
							<div class="dropdown">
								<a class="btn btn-secondary dropdown-toggle p-0 bg-transparent border-0 text-dark shadow-none font-weight-medium" href="#" role="button" id="dropdownMenuLinkA" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<h5 class="mb-0 d-inline-block">26 Jul 2018</h5>
								</a>
							</div>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Revenue</small>
							<h5 class="mr-2 mb-0">$577545</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-eye mr-3 icon-lg text-success"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Total views</small>
							<h5 class="mr-2 mb-0">9833550</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-download mr-3 icon-lg text-warning"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Downloads</small>
							<h5 class="mr-2 mb-0">2233783</h5>
							</div>
						</div>
						<div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-flag mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Flagged</small>
							<h5 class="mr-2 mb-0">3497843</h5>
							</div>
						</div>
						</div>
					</div>
					<div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
						<div class="d-flex flex-wrap justify-content-xl-between">
						<div class="d-none d-xl-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-calendar-heart icon-lg mr-3 text-primary"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Start date</small>
							<div class="dropdown">
								<a class="btn btn-secondary dropdown-toggle p-0 bg-transparent border-0 text-dark shadow-none font-weight-medium" href="#" role="button" id="dropdownMenuLinkA" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<h5 class="mb-0 d-inline-block">26 Jul 2018</h5>
								</a>
							</div>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-download mr-3 icon-lg text-warning"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Downloads</small>
							<h5 class="mr-2 mb-0">2233783</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-eye mr-3 icon-lg text-success"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Total views</small>
							<h5 class="mr-2 mb-0">9833550</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Revenue</small>
							<h5 class="mr-2 mb-0">$577545</h5>
							</div>
						</div>
						<div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-flag mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Flagged</small>
							<h5 class="mr-2 mb-0">3497843</h5>
							</div>
						</div>
						</div>
					</div>
					<div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
						<div class="d-flex flex-wrap justify-content-xl-between">
						<div class="d-none d-xl-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-calendar-heart icon-lg mr-3 text-primary"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Start date</small>
							<div class="dropdown">
								<a class="btn btn-secondary dropdown-toggle p-0 bg-transparent border-0 text-dark shadow-none font-weight-medium" href="#" role="button" id="dropdownMenuLinkA" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<h5 class="mb-0 d-inline-block">26 Jul 2018</h5>
								</a>
							</div>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Revenue</small>
							<h5 class="mr-2 mb-0">$577545</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-eye mr-3 icon-lg text-success"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Total views</small>
							<h5 class="mr-2 mb-0">9833550</h5>
							</div>
						</div>
						<div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-download mr-3 icon-lg text-warning"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Downloads</small>
							<h5 class="mr-2 mb-0">2233783</h5>
							</div>
						</div>
						<div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
							<i class="mdi mdi-flag mr-3 icon-lg text-danger"></i>
							<div class="d-flex flex-column justify-content-around">
							<small class="mb-1 text-muted">Flagged</small>
							<h5 class="mr-2 mb-0">3497843</h5>
							</div>
						</div>
						</div>
					</div>
					</div>
				</div>
				</div>
			</div>
		</div> -->
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="flex justify-between">
						<p class="card-title">Things To-Do Today</p>
						<span>
							<button @click="openNew()" class="btn btn-xs btn-primary px-1 py-1">
								<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-3 h-3"></PlusIcon>
							</button>
						</span>
					</div>
					<div>
						<table class="w-full table-bordered">
							<tr class="bg-gray-100">
								<td class="p-1 text-bold">Description</td>
								<td width="2%"><input type="checkbox"  @click="selectAll('no')" v-model="allSelected"></td>
							</tr>
							<tr v-for="t in todo_list">
								<td class="p-1">{{t.todo_description}}</td>
								<td width="2%"><input type="checkbox" v-model="selected_todo" v-on:click="select" :value="t.id" @click="checkCompletetodo(t.id,'no')"></td>
							</tr>
						</table>
					</div>
				</div>
				</div>
			</div>
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="flex justify-between">
							<p class="card-title">Reminder</p>
							<span>
								<button @click="openNewReminder()" class="btn btn-xs btn-primary px-1 py-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-3 h-3"></PlusIcon>
								</button>
							</span>
						</div>
						<div>
							<table class="w-full table-bordered">
								<tr class="bg-gray-100">
									<td class="p-1 text-bold text-center" width="15%">Due Date</td>
									<td class="p-1 text-bold">Description</td>
									<td class="p-1 text-center text-bold" width="10%">Days Left</td>
									<td width="2%" class="p-q text-center"><input type="checkbox"  @click="selectAllreminder('no')" v-model="allSelectedreminder"></td>
								</tr>
								<tr v-for="r in reminder_list">
									<td :class="(daysRemaining(r.reminder_due_date)<0) ? 'bg-red-100 p-1 text-center' : 'p-1 text-center'" width="15%">{{r.reminder_due_date}}</td>
									<td :class="(daysRemaining(r.reminder_due_date)<0) ? 'bg-red-100' : 'p-1'">{{r.reminder_desc}}</td>
									<td :class="(daysRemaining(r.reminder_due_date)<0) ? 'bg-red-100 text-center' : 'p-1 text-center'" width="20%">{{ (r.reminder_due_date!='' ) ? daysRemaining(r.reminder_due_date) : 0}} Day/s </td>
									<td :class="(daysRemaining(r.reminder_due_date)<0) ? 'bg-red-100 text-center' : 'p-1 text-center'" width="2%"><input type="checkbox" v-model="selected_reminder" v-on:click="selectreminder" :value="r.id" @click="checkCompletereminder(r.id,'no')"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:modalNew }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Things to do</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Description</label>
									<textarea class="form-control" placeholder="Description" v-model="form.todo_description" rows="3"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button  @click="onSave()" class="btn btn-primary mr-2 w-44" id='save'>Save</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:modalNewReminder }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Reminder</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Due Date</label>
									<input type="date" class="form-control" placeholder="Due Date" v-model="reminderform.reminder_due_date">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Description</label>
									<textarea class="form-control" placeholder="Description" rows="3" v-model="reminderform.reminder_desc"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button  @click="onSaveReminder()" class="btn btn-primary mr-2 w-44" id='save'>Save</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">{{ success }}</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn  btn-sm !rounded-full w-full"  @click="closeModal()">Close</button>
									<button class="btn !bg-gray-100 !text-white !bg-green-500 btn-sm !rounded-full w-full"  @click="closeAlert()">Create New</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertComplete }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">{{ success }}</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Error!</h2>
									<h5 class="leading-tight" v-if="error">{{ error }}</h5>
									<h5 class="leading-tight" v-else-if="error_reminder!=''" v-for="er in error_reminder">{{ er }}</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to complete this {{label}}?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="checkCompletetodo(todo_id,'yes')" v-if="label=='todo'">Yes</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="checkCompletereminder(reminder_id,'yes')" v-else-if="label=='reminder'">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>

		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_itemcomplete }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to complete all of this {{label}}?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="selectAll('yes')" v-if="label=='todo'">Yes</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="selectAllreminder('yes')" v-else-if="label=='reminder'">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>