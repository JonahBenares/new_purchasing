<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{HomeIcon, UserIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
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
									<input class="form-control" placeholder="Employee Name">
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" >Department</label>
									<input class="form-control" placeholder="Department">
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" >Position</label>
									<input class="form-control" placeholder="Position">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group mt-3">
									<div class="flex justify-center space-x-2">
										<input class="form-control !w-5"  id="checkbox"  type="checkbox" @click="showCredentials()">
										<label class="form-check-label text-xs mb-0"> Check the box if employee can access the system.</label>
									</div>
								</div>
								<hr class="mb-3">
								<div class="" style="display: none;" id="showCred">
									<div class="form-group">
										<label class="text-gray-500 m-0" >Email</label>
										<input type="text" class="form-control border">
									</div>	
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="text-gray-500 m-0" >Password</label>
												<input type="text" class="form-control border" readonly>
											</div>										
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="text-gray-500 m-0" >Type</label>
												<select class="form-control border">
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
									<button type="submit" class="btn btn-primary mr-2 w-44">Submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				</div>
			</div>
		</div>
	</navigation>
</template>