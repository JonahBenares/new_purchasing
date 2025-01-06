<script setup>
import navigation from '@/layouts/navigation.vue';
import { PencilSquareIcon, TrashIcon, PlusIcon, MagnifyingGlassIcon, ChevronLeftIcon, ChevronRightIcon, ArrowUturnLeftIcon ,CheckIcon, XMarkIcon} from '@heroicons/vue/24/solid'
import {onMounted, ref} from "vue";
import { useRouter } from "vue-router"
const router = useRouter() //use if link is used inside the page
let form=ref([]);
let error = ref('')
let success = ref('')
let credentials=ref([]);
let errorMessage=ref('');
let errorMessageLength=ref('');
const dangerAlert = ref(false)
const successAlert = ref(false)
const hideAlert = ref(true)
onMounted(async () => {
	getCredentials()
	credentialForm()
})
const getCredentials = async () => {
	const response = await fetch(`/api/change_password`);
    credentials.value = await response.json();
}

const credentialForm = async () => {
	let response = await axios.get("/api/create_credential");
	form.value = response.data;
}

const checkLengthPass = () => {
	var newpassword = document.getElementById('newpassword');
	const button = document.getElementById("btnSave");
	if(newpassword.value.length < 6){
		errorMessageLength.value = 'You have to enter at least 6 digit!';
		button.disabled = true;
	}else{
		errorMessageLength.value = ''
		button.disabled = false;
	}
}

const validateForm = () => {
	const button = document.getElementById("btnSave");
	if (form.value.new_password !== form.value.confirm_password) {
		errorMessage.value = 'Passwords do not match';
		button.disabled = true;
		return false;
	}
	errorMessage.value = '';
	button.disabled = false;
	return true;
}

const onSave = () => {
	const formData=new FormData()
	formData.append('password',form.value.new_password)
	formData.append('user_id',credentials.value.id)
	axios.post("/api/edit_password",formData).then(function (response) {
		error.value=''
		success.value='Successfully changed password!'
		successAlert.value = !successAlert.value
		setTimeout(() => {
			localStorage.removeItem('token')
			router.push('/')
		}, 2000);
	}).catch(function(err){
		success.value=''
		error.value=''
		if (err.response.data.errors.password) {
			error.value=err.response.data.errors.password[0]
		}
	});
}
	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
	}
</script>

<template>
    <navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">{{credentials.name}} </h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-lg-12 stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="mt-2 mb-2 border-b">
							<h6>Change Password</h6>	
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="form-label">New Password</label>
									<input type="password" id="newpassword" class="form-control border" placeholder="New Password" v-model="form.new_password" @keyup="checkLengthPass()">
									<p v-if="errorMessageLength" style="color:red">{{ errorMessageLength }}</p>
								</div>		
								<div class="form-group">
									<label class="form-label">Retype New Password</label>
									<input type="password" class="form-control border" placeholder="Retype New Password" @keyup="validateForm()" v-model="form.confirm_password">
									<p v-if="errorMessage" style="color:red">{{ errorMessage }}</p>
								</div>									
							</div>
						</div>
						<div class="pt-2 mb-2">
							<input type="hidden" class="form-control border" v-model="credentials.id">
							<button @click="onSave()" id="btnSave" class="btn btn-sm btn-primary btn-rounded w-32">Submit</button>
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
									<h5 class="leading-tight" >{{ error }}</h5>
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
