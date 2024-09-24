<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{HomeIcon, UserIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	let form=ref([]);
	let success=ref('');
	let error=ref([]);
	let department_list=ref([]);
	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const hideAlert = ref(true)
	onMounted(async () => {
		employeesForm()
		getDepartment()
	})
    const employeesForm = async () => {
		let response = await axios.get("/api/create_employee");
		form.value = response.data;
	}
	const getDepartment = async () => {
		let response = await axios.get("/api/get_department");
		department_list.value = response.data.department;
	}
	const onSave = () => {
		const formData= new FormData()
		let checkbox=document.getElementById('checkbox');
		if(checkbox.checked){
			var access=1
			formData.append('temp_password',form.value.temp_password)
			formData.append('password',form.value.temp_password)
		}else{
			var access=0
			formData.append('temp_password','')
			formData.append('password','')
		}
		formData.append('name', form.value.name)
		formData.append('email', form.value.email)
		formData.append('department_id', form.value.department_id)
		formData.append('position', form.value.position)
		formData.append('access', access)
		formData.append('user_type', form.value.user_type)
		axios.post("/api/add_employee",formData).then(function () {
			success.value='You have successfully added new employee!'
			// form.value=[]
			error.value=[]
			form.value.employee_name=''
			form.value.department_id=''
			form.value.position=''
			successAlert.value = !successAlert.value
			let show=document.getElementById('showCred');
			show.style.display = 'none'; 
			getDepartment()
			employeesForm()
			setTimeout(() => {
				closeAlert()
			}, 2000);
		}, function (err) {
			// error.value = err.response.data.message;
			error.value=[]
			if (err.response.data.errors.name) {
				error.value.push(err.response.data.errors.name[0])
			}
			if (err.response.data.errors.position) {
				error.value.push(err.response.data.errors.position[0])
			}
			if (err.response.data.errors.department_id) {
				error.value.push(err.response.data.errors.department_id[0])
			}
			if (err.response.data.errors.email) {
				error.value.push(err.response.data.errors.email[0])
			}
			if (err.response.data.errors.temp_password) {
				error.value.push(err.response.data.errors.temp_password[0])
			} 
			if (err.response.data.errors.user_type) {
				error.value.push(err.response.data.errors.user_type[0])
			}
			dangerAlert.value = !dangerAlert.value
			getDepartment()
		});
	}

	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
	}
	const showCredentials = () => {
		let show=document.getElementById('showCred');
		let checkbox=document.getElementById('checkbox');
		if(checkbox.checked){
			show.style.display = 'block';   
		}else{
			show.style.display = 'none'; 
		}
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Employee <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/employee">Employee List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="pt-2">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Employee Name</label>
									<input class="form-control" placeholder="Employee Name" v-model="form.name">
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" >Department</label>
									<select class="form-control" v-model="form.department_id">
										<option value=''>--Select Department--</option>
										<option :value="dept.id" v-for="dept in department_list" :key="dept.id">{{ dept.department_name }}</option>
									</select>
									<!-- <input class="form-control" placeholder="Department"> -->
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" >Position</label>
									<input type="text" class="form-control" placeholder="Position" v-model="form.position">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group mt-3">
									<div class="flex justify-center space-x-2">
										<input class="form-control !w-5"  id="checkbox"  type="checkbox" @click="showCredentials()" v-model="form.access">
										<label class="form-check-label text-xs mb-0"> Check the box if employee can access the system.</label>
									</div>
								</div>
								<hr class="mb-3">
								<div class="" style="display: none;" id="showCred">
									<div class="form-group">
										<label class="text-gray-500 m-0" >Email</label>
										<input type="email" class="form-control border" v-model="form.email">
									</div>	
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="text-gray-500 m-0" >Password</label>
												<input type="text" class="form-control border" v-model="form.temp_password" readonly>
											</div>										
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="text-gray-500 m-0" >Type</label>
												<select class="form-control border" v-model="form.user_type">
													<option value="Admin">Admin</option>
													<option value="Staff">Staff</option>
												</select>
											</div>										
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<hr class="border-dashed">
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button class="btn btn-light">Cancel</button> -->
									<button type="button" @click="onSave()" class="btn btn-primary mr-2 w-44">Submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				</div>
			</div>
		</div>
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
									<h5 class="leading-tight" v-for="er in error">{{ er }}</h5>
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
	</navigation>
</template>