<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{HomeIcon, UserIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Vendor <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/vendor">Vendor List</a></li>
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
									<label class="text-gray-500 m-0" >Vendor Name</label>
									<textarea class="form-control" placeholder="Vendor Name"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Products/Services</label>
									<textarea class="form-control" placeholder="Products/Services"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Address</label>
									<textarea class="form-control" placeholder="Address"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Terms</label>
									<textarea class="form-control" placeholder="Terms"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Phone Number</label>
									<input type="text" class="form-control" placeholder="Phone Number">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Fax Number</label>
									<input type="text" class="form-control" placeholder="Fax Number">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Contact Person</label>
									<input type="text" class="form-control" placeholder="Contact Person">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Email</label>
									<input type="email" class="form-control" placeholder="Email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >TIN</label>
									<input type="text" class="form-control" placeholder="TIN">
								</div>
							</div>
							<div class="col-lg-3 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Type</label>
									<input type="text" class="form-control" placeholder="Type">
								</div>
							</div>
							<div class="col-lg-2 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >EWT%</label>
									<input type="text" class="form-control" placeholder="EWT%">
								</div>
							</div>
							<div class="col-lg-2 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Status</label>
									<select class="form-control">
										<option value="">Inactive</option>
										<option value="">Active</option>
									</select>
								</div>
							</div>
							<div class="col-lg-2 col-md-2">
								<div class="flex !justify-center mt-4 space-x-4">
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Notes</label>
									<textarea class="form-control" placeholder="Notes"></textarea>
								</div>
							</div>
						</div>
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
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