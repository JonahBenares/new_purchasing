<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import { onMounted, ref, watch } from "vue"
    import { useRouter } from "vue-router"
	const router = useRouter();
	let prheadid=ref(0);
	let pr_head_id=ref(0);
	let pr_details_id=ref(0);
	let prhead=ref([]);
	let prdetails=ref([]);
	let form=ref([]);
	let item_list=ref([]);
	let department_list=ref([]);
	let error=ref('');
	let error_pr=ref([]);
	let success=ref('');
	let item_no=ref();
	let qty=ref('');
	let uom=ref('');
	let pn_no=ref('');
	let item_desc=ref('');
	let wh_stocks=ref(0);
	let date_needed=ref('');
	let recom_date=ref('');
	const pr_options = ref();
	let check_pr=ref(true)
	let prFile=ref("");
	let prUrl=ref("");
	let error_inventory=ref("");
	const dangerAlerterrors = ref(false)
	let recom_date_update=ref([]);
	let signatories=ref([]);
	let approved_date=ref("");
	let remarks=ref("");
	let prepared_by=ref("");
	let recommended_by=ref("");
	let approved_by=ref("");
	let processing_code=ref([]);
	let petty_cash=ref([]);
	let pr_series=ref('0');
	const loading = ref(false)
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		prForm()
		getDepartment()
		getSignatories()
		getProcesscode()
		if(props.id!=0){
			getImportdata(props.id)
		}
	})
	const getProcesscode = async () => {
		let response = await axios.get("/api/processing_code");
		processing_code.value = response.data;
	}
	const prForm = async () => {
		if(props.id==0){
			let response = await axios.get("/api/create_pr");
			form.value = response.data;
		}else{
			let response = await axios.get('/api/get_import_data/'+props.id);
			form.value = response.data.pr_head;
		}
	}
	const getDepartment = async () => {
		let response = await axios.get("/api/get_department");
		department_list.value = response.data.department;
	}
	const getImportdata = async (id) => {
		if(props.id!=0){
			pr_head_id.value=props.id
		}
		let response = await axios.get('/api/get_import_data/'+id);
		prhead.value = response.data.pr_head;
		prdetails.value = response.data.pr_details;
		if(prhead.value.petty_cash==1){
			petty_cash.value = response.data.petty_cash;
		}
	}
	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}

	// const add = (arr, name) => {
	// 	const { length } = arr;
	// 	const id = length + 1;
	// 	const found = arr.some(el => el.quantity === name);
	// 	if (!found) arr.push({ id, quantity: name });
	// 	return arr;
	// }

	const addItem= () => {
		if(qty.value == ''){
			// alert("Quantity must not be empty!")
			document.getElementById('check_qty').placeholder="Quantity must not be empty!"
			document.getElementById('check_qty').style.backgroundColor = '#FAA0A0';
		}else if(uom.value == ''){
			// alert("Uom must not be empty!")
			document.getElementById('check_uom').placeholder="Uom must not be empty!"
			document.getElementById('check_uom').style.backgroundColor = '#FAA0A0';
		}else if(item_desc.value == ''){
			// alert("Item Description must not be empty!")
			document.getElementById('check_description').placeholder="Item Description must not be empty!"
			document.getElementById('check_description').style.backgroundColor = '#FAA0A0';
		}else{
			document.getElementById('check_qty').placeholder="Qty"
			document.getElementById('check_qty').style.backgroundColor = '#FFFFFF';
			document.getElementById('check_uom').placeholder="UOM"
			document.getElementById('check_uom').style.backgroundColor = '#FFFFFF';
			document.getElementById('check_description').placeholder="Item Description"
			document.getElementById('check_description').style.backgroundColor = '#FFFFFF';
			const items = {
				item_no:item_no.value,
				qty:qty.value,
				uom:uom.value,
				pn_no:pn_no.value,
				item_desc:item_desc.value,
				wh_stocks:wh_stocks.value,
				date_needed:date_needed.value,
				recom_date:recom_date.value,
			}
			// console.log(item_list.value);
			// item_list.value.push(items)
			if(item_list.value.length==0 && prdetails.value.length==0){
				item_list.value.push(items)
			}else{
				const ObjqtyToFind = items.qty;
				const ObjuomToFind = items.uom;
				const Objpn_noToFind = items.pn_no;
				const Objitem_descToFind = items.item_desc;
				const Objwh_stocksToFind = items.wh_stocks;
				const Objdate_neededToFind = items.date_needed;
				const Objrecom_dateToFind = items.recom_date;
				if(prdetails.value.length==0){
					if (!item_list.value.find((o) => o.qty === ObjqtyToFind) || !item_list.value.find((o) => o.uom === ObjuomToFind) || !item_list.value.find((o) => o.pn_no === Objpn_noToFind) || !item_list.value.find((o) => o.item_desc === Objitem_descToFind) || !item_list.value.find((o) => o.wh_stocks === Objwh_stocksToFind)) {  
						item_list.value.push(items);
					}else{
						error.value="Duplicate entry! This item already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}else{
					for(var x=0; x<prdetails.value.length; x++){
						if((prdetails.value[x].quantity == items.qty && prdetails.value[x].uom == items.uom && prdetails.value[x].pn_no == items.pn_no &&  prdetails.value[x].item_description == items.item_desc && prdetails.value[x].wh_stocks == items.wh_stocks) || (item_list.value.find((o) => o.qty === ObjqtyToFind) && item_list.value.find((o) => o.uom === ObjuomToFind) && item_list.value.find((o) => o.pn_no === Objpn_noToFind) && item_list.value.find((o) => o.item_desc === Objitem_descToFind) && item_list.value.find((o) => o.wh_stocks === Objwh_stocksToFind))){
							var checker = true; 
						}
					}
					if (checker==undefined) {  
						item_list.value.push(items);
					}else{
						error.value="Duplicate entry! This item already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}
			}
			qty.value='';
			uom.value='';
			pn_no.value='';
			item_desc.value='';
			wh_stocks.value=0;
			date_needed.value='';
			recom_date.value='';
		}
	}

	const removeItem = (index) => {
		item_list.value.splice(index,1)
		dangerAlert_item.value = !hideAlert.value
	}

	const removeItem1 = () => {
		const item1 = document.getElementById("item1");
		item1.remove();
		dangerAlert_item1.value = !hideAlert.value
	}

	const removeItem2 = () => {
		const item1 = document.getElementById("item2");
		item1.remove();
		dangerAlert_item2.value = !hideAlert.value
	}

	const removeItem3 = () => {
		const item1 = document.getElementById("item3");
		item1.remove();
	}

	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const dangerAlert_item1 = ref(false)
	const dangerAlert_item2 = ref(false)
	const successAlert = ref(false)
	const successAlertCD = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
	const openDangerAlert_item = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
	const openDangerAlert_item1 = (id) => {
		dangerAlert_item1.value = !dangerAlert_item1.value
		pr_details_id.value=id
	}

	const deleteItem = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_item/`+id).then(function () {
				dangerAlert_item1.value = !hideAlert.value
				success.value='Successfully deleted item!'
				successAlertCD.value = !successAlertCD.value
				getImportdata(pr_head_id.value)
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			pr_details_id.value=id
			dangerAlert_item1.value = !dangerAlert_item1.value
		}
	}

	const cancelPr = (option) => {
		if(option=='yes'){
			axios.get(`/api/cancel_pr/`+pr_head_id.value).then(function () {
				dangerAlert.value = !hideAlert.value
				success.value='Successfully cancelled pr!'
				successAlertCD.value = !successAlertCD.value
				setTimeout(() => {
					window.location.reload()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			dangerAlert.value = !dangerAlert.value
		}
	}

	const openDangerAlert_item2 = () => {
		dangerAlert_item2.value = !dangerAlert_item2.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}

	const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeAlert = () => {
		dangerAlerterrors.value = !hideAlert.value
		successAlert.value = !hideAlert.value
		successAlertCD.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert_item1.value = !hideAlert.value
		dangerAlert_item2.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
	}

	const showSigna = () => {
		let show=document.getElementById('showsigna');
		let checkbox=document.getElementById('checkbox');
		if(checkbox.checked){
			show.style.display = 'block';   
		}else{
			show.style.display = 'none'; 
		}
	}

	const upload_pr = (event) => {
		const btn_pr = document.getElementById("btn_pr");
		btn_pr.disabled = false;
		let file = event.target.files[0];
		if(event.target.files.length===0){
			prFile.value='';
			prUrl.value='';
			return;
		}else if(file['size'] < 2111775){
			prFile.value = event.target.files[0];
			error_inventory.value=''
		}else{
			prUrl.value='';
			error_inventory.value='File size cannot be bigger than 2 MB'
			dangerAlerterrors.value = !dangerAlerterrors.value
			btn_pr.disabled = true;
		}
	}
	
	watch(prFile, (prFile) => {
		if(!(prFile instanceof File)){
			return;
		}
		let fileReader = new FileReader();
		fileReader.readAsDataURL(prFile)
		fileReader.addEventListener("load", () => {
			prUrl.value=fileReader.result
		})
	})

	const importSave = () => {
		const formData= new FormData()
		formData.append('upload_pr',prFile.value)
		loading.value=true;
		pr_options.value='';
		try {
			axios.post("/api/import_pr",formData).then(function (response) {
				pr_head_id.value=response.data.pr_head_id
				getImportdata(pr_head_id.value)
				// prhead.value=response.data.prhead
				// prdetails.value=response.data.prdetails
				// success.value='You have successfully imported new pr.'
				// successAlert.value=!successAlert.value
				pr_options.value='pr_upload';
				loading.value=false;
				prFile.value=''
				const btn_pr = document.getElementById("btn_pr");
				btn_pr.disabled = true;
			}, function (err) {
				loading.value=false;
				var substring="1048 Column 'department_id'"
				var substring1="floor(): Argument #1"
				if(err.response.data.message.includes(substring)==true){
					error.value = 'Department name does not exist, make sure it is existing in deparment masterfile.';
					document.getElementById('upload_pr').value=''
					prFile.value=''
					pr_options.value='';
					const btn_pr = document.getElementById("btn_pr");
					btn_pr.disabled = true;
				}else if(err.response.data.message.includes(substring1)==true){
					error.value = 'Invalid file format. Please upload another file with correct format.';
					document.getElementById('upload_pr').value=''
					prFile.value=''
					pr_options.value='';
					const btn_pr = document.getElementById("btn_pr");
					btn_pr.disabled = true;
				}else{
					error.value = err.response.data.message;
				}
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		} catch (error) {

		} 
    }
	
	const updateRecomdate = (id) => {
		const formData= new FormData()
		if(props.id!=0){
			formData.append('recom_date', JSON.stringify(prdetails.value))
			var api='update_recomdate_view';
		}else{
			formData.append('recom_date', JSON.stringify(recom_date_update.value))
			var api='update_recomdate/'+id;
		}
		axios.post('/api/'+api,formData).then(function () {
			recom_date_update.value=[]
			getImportdata(pr_head_id.value)
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const onSave = () => {
		const formData= new FormData()
		// formData.append('prhead', JSON.stringify(prhead.value))
		formData.append('location', prhead.value.location)
		formData.append('pr_no', prhead.value.pr_no)
		formData.append('site_pr', prhead.value.site_pr)
		formData.append('date_prepared', prhead.value.date_prepared)
		formData.append('date_issued', prhead.value.date_issued)
		formData.append('department_id', prhead.value.department_id)
		formData.append('urgency', prhead.value.urgency)
		formData.append('process_code', prhead.value.process_code)
		formData.append('requestor', prhead.value.requestor)
		formData.append('enduse', prhead.value.enduse)
		formData.append('purpose', prhead.value.purpose)
		formData.append('petty_cash', prhead.value.petty_cash)
		formData.append('prdetails', JSON.stringify(prdetails.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		if(props.id==0){
			formData.append('approved_date', approved_date.value)
			formData.append('remarks', remarks.value)
			formData.append('prepared_by', prepared_by.value)
			formData.append('recommended_by', recommended_by.value)
			formData.append('approved_by', approved_by.value)
		}else{
			formData.append('petty_cash_id', petty_cash.value.id)
			formData.append('approved_date', petty_cash.value.approved_date)
			formData.append('remarks', petty_cash.value.remarks)
			formData.append('prepared_by', petty_cash.value.prepared_by)
			formData.append('recommended_by', petty_cash.value.recommended_by)
			formData.append('approved_by', petty_cash.value.approved_by)
		}
		formData.append('props_id', props.id)
		if(prdetails.value.length!=0 || item_list.value.length!=0){
			axios.post(`/api/save_upload/${pr_head_id.value}`,formData).then(function (response) {
				if(prhead.value.petty_cash==0){
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
				}else{
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
					router.push('/pur_req/view/'+prhead.value.id)
				}
				item_list.value=[]
				getImportdata(pr_head_id.value)
			}, function (err) {
				error.value = err.response.data.message;
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			error.value = 'Items cannot be empty, Please fill in data.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}
    }

	const onSaveDraftUpload = () => {
		const formData= new FormData()
		// formData.append('prhead', JSON.stringify(prhead.value))
		formData.append('location', prhead.value.location)
		formData.append('pr_no', prhead.value.pr_no)
		formData.append('site_pr', prhead.value.site_pr)
		formData.append('date_prepared', prhead.value.date_prepared)
		formData.append('date_issued', prhead.value.date_issued)
		formData.append('department_id', prhead.value.department_id)
		formData.append('urgency', prhead.value.urgency)
		formData.append('process_code', prhead.value.process_code)
		formData.append('requestor', prhead.value.requestor)
		formData.append('enduse', prhead.value.enduse)
		formData.append('purpose', prhead.value.purpose)
		formData.append('petty_cash', prhead.value.petty_cash)
		formData.append('prdetails', JSON.stringify(prdetails.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		// formData.append('approved_date', approved_date.value)
		// formData.append('remarks', remarks.value)
		// formData.append('prepared_by', prepared_by.value)
		// formData.append('recommended_by', recommended_by.value)
		// formData.append('approved_by', approved_by.value)
		if(props.id==0){
			formData.append('approved_date', approved_date.value)
			formData.append('remarks', remarks.value)
			formData.append('prepared_by', prepared_by.value)
			formData.append('recommended_by', recommended_by.value)
			formData.append('approved_by', approved_by.value)
		}else{
			formData.append('petty_cash_id', petty_cash.value.id)
			formData.append('approved_date', petty_cash.value.approved_date)
			formData.append('remarks', petty_cash.value.remarks)
			formData.append('prepared_by', petty_cash.value.prepared_by)
			formData.append('recommended_by', petty_cash.value.recommended_by)
			formData.append('approved_by', petty_cash.value.approved_by)
		}
		formData.append('props_id', props.id)
		axios.post(`/api/save_upload_draft/${pr_head_id.value}`,formData).then(function (response) {
			success.value='You have successfully draft new pr.'
			warningAlert.value=!warningAlert.value
			item_list.value=[]
			getImportdata(pr_head_id.value)
			
			// setTimeout(() => {
			// 	closeAlert()
			// }, 2000);
		}, function (err) {
			error.value = err.response.data.message;
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
    }

	const generatePrNo = () => {
		const formData= new FormData()
		if(props.id==0){
			if(prhead.value.pr_no!='' && prhead.value.pr_no!=undefined){
				formData.append('date_prepared', prhead.value.date_prepared)
				formData.append('department',  prhead.value.department_id)
				formData.append('series', pr_series.value)
				formData.append('pr_no',  prhead.value.pr_no)
			}else{
				formData.append('date_prepared', form.value.date_prepared)
				formData.append('department', form.value.department)
				formData.append('series', pr_series.value)
				formData.append('pr_no', form.value.pr_no)
			}
		}else{
			formData.append('date_prepared', prhead.value.date_prepared)
			formData.append('department',  prhead.value.department_id)
			formData.append('series', pr_series.value)
			formData.append('pr_no',  prhead.value.pr_no)
		}
		formData.append('props_id', props.id)
		axios.post(`/api/generate_prno`,formData).then(function (response) {
			if(props.id==0){
				if(prhead.value.pr_no!='' && prhead.value.pr_no!=undefined){
					prhead.value.pr_no=response.data
				}else{
					form.value.pr_no=response.data
				}
			}else{
				prhead.value.pr_no=response.data
			}
			// form.value.pr_no=response.data.pr_no
			// pr_series.value=response.data.pr_series
			// var date = (form.value.date_prepared!=undefined) ? form.value.date_prepared : '';
			// insertSeries(response.data.pr_series,date,form.value.department)
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const insertSeries = (series,date,department) => {
		const formData= new FormData()
		formData.append('series', series)
		formData.append('date', date)
		formData.append('pr_no', form.value.pr_no)
		formData.append('department', department)
		axios.post(`/api/insert_series`,formData).then(function (response) {
			form.value.pr_no=response.data.pr_no
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const onSaveManual = () => {
		const formData= new FormData()
		formData.append('location', form.value.purchase_request)
		formData.append('pr_no', form.value.pr_no)
		formData.append('site_pr', form.value.site_pr)
		formData.append('date_issued', form.value.date_issued)
		formData.append('date_prepared', form.value.date_prepared)
		formData.append('department_id', form.value.department)
		formData.append('urgency', form.value.urgency)
		formData.append('process_code', form.value.process_code)
		formData.append('requestor', form.value.requestor)
		formData.append('enduse', form.value.enduse)
		formData.append('purpose', form.value.purpose)
		formData.append('petty_cash', form.value.petty_cash)
		formData.append('approved_date', approved_date.value)
		formData.append('remarks', remarks.value)
		formData.append('prepared_by', prepared_by.value)
		formData.append('recommended_by', recommended_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('series', pr_series.value)
		formData.append('prhead_id', prheadid.value)
		formData.append('item_list', JSON.stringify(item_list.value))
		if(item_list.value.length!=0){
			axios.post(`/api/save_manual`,formData).then(function (response) {
				// success.value='You have successfully saved new pr.'
				// successAlert.value=!successAlert.value
				prheadid.value=response.data;
				if(form.value.petty_cash==0){
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
				}else{
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
					router.push('/pur_req/view/'+prheadid.value)
				}
			}, function (err) {
				// error.value = err.response.data.message;
				error.value=''
				error_pr.value=[]
				if (err.response.data.errors.pr_no) {
					error_pr.value.push(err.response.data.errors.pr_no[0])
				}
				if (err.response.data.errors.department_id) {
					error_pr.value.push(err.response.data.errors.department_id[0])
				}
				if (err.response.data.errors.location) {
					error_pr.value.push(err.response.data.errors.location[0])
				}
				if (err.response.data.errors.date_prepared) {
					error_pr.value.push(err.response.data.errors.date_prepared[0])
				}
				if (err.response.data.errors.requestor) {
					error_pr.value.push(err.response.data.errors.requestor[0])
				}
				if (err.response.data.errors.enduse) {
					error_pr.value.push(err.response.data.errors.enduse[0])
				}
				if (err.response.data.errors.purpose) {
					error_pr.value.push(err.response.data.errors.purpose[0])
				}
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			error.value=''
			error.value = 'Items cannot be empty, Please fill in data.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}
    }

	const onSaveDraftManual = () => {
		const formData= new FormData()
		formData.append('location', form.value.purchase_request)
		formData.append('pr_no', form.value.pr_no)
		formData.append('site_pr', form.value.site_pr)
		formData.append('date_issued', form.value.date_issued)
		formData.append('date_prepared', form.value.date_prepared)
		formData.append('department_id', form.value.department)
		formData.append('urgency', form.value.urgency)
		formData.append('process_code', form.value.process_code)
		formData.append('requestor', form.value.requestor)
		formData.append('enduse', form.value.enduse)
		formData.append('purpose', form.value.purpose)
		formData.append('petty_cash', form.value.petty_cash)
		formData.append('approved_date', approved_date.value)
		formData.append('remarks', remarks.value)
		formData.append('prepared_by', prepared_by.value)
		formData.append('recommended_by', recommended_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('series', pr_series.value)
		formData.append('prhead_id', prheadid.value)
		formData.append('item_list', JSON.stringify(item_list.value))
		axios.post(`/api/save_manual_draft`,formData).then(function (response) {
			// console.log(response.data)
			success.value='You have successfully saved new pr.'
			warningAlert.value=!warningAlert.value
			prheadid.value=response.data;
			const btn_draft = document.getElementById("btn_draft");
			btn_draft.disabled = true;
			setTimeout(() => {
				btn_draft.disabled = false;
			}, 500);
		}, function (err) {
			error_pr.value=[]
			// error_pr.value = err.response.data.message;
			if (err.response.data.errors.pr_no) {
				error_pr.value.push(err.response.data.errors.pr_no[0])
			}
			if (err.response.data.errors.department_id) {
				error_pr.value.push(err.response.data.errors.department_id[0])
			}
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
    }
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-if="prhead.status=='Draft' && props.id!=0">Purchase Request <small>Draft</small></h3>
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-else>Purchase Request <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent" >
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_req">Purchase Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page" v-if="prhead.status=='Draft' && props.id!=0">Draft</li>
                            <li class="breadcrumb-item active" aria-current="page" v-else>New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div class="row">							
							<div class="col-lg-5 offset-lg-1 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Import PR Excel File here or Manual encode below</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<input type="file" class="form-control file-upload-info" id="upload_pr" name="upload_pr" @change="upload_pr" placeholder="Upload Image">
									<span class="input-group-append">
										<button class="btn btn-primary" :disabled="check_pr" id="btn_pr" @click="importSave()" v-on:click="pr_options = ''">Upload</button>
										<!-- <button class="btn btn-primary" type="button" :disabled="check_pr" id="btn_pr" v-on:click="pr_options = 'pr_upload'">Upload</button> -->
									</span>
								</div>
								</div>
							</div>
							<div class="col-lg-1">
								<span class="text-center w-full block text-lg mt-4 text-gray-400">OR</span>
							</div>
							<div class="col-lg-4 col-md-3">
								<div class="form-group m-0">
									<label class="text-gray-500 m-0" for="">Add PR and Items Manually</label>
								</div>
								<button class="btn btn-primary btn-block" type="button" v-on:click="pr_options = 'pr_manual'" v-if="prhead.status=='Draft' && props.id!=0" disabled>Manual PR</button>
								<button class="btn btn-primary btn-block" type="button" v-on:click="pr_options = 'pr_manual'" v-else>Manual PR</button>
							</div>
						</div>
						<div class="mt-[300px] font-bold text-base" v-if="loading"><center>Loading data, please wait...</center></div>
						<div class="" id="upload" v-if="pr_options === 'pr_upload'  || props.id!=0">
							<hr class="border-dashed">
							<!-- <div v-for="head in prhead"> -->
							<p class="text-gray-500 font-bold text-lg" v-if="prhead.status!='Draft'">From Uploaded File</p>
							<p class="text-gray-500 font-bold text-lg" v-else>DRAFT</p>
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Location</label>
										<input type="text" class="form-control" placeholder="Location" v-model="prhead.location">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">PR No</label>
										<input type="text" class="form-control" placeholder="PR No" v-model="prhead.pr_no" readonly>
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Site PR No</label>
										<input type="text" class="form-control" placeholder="Site PR No" v-model="prhead.site_pr">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Prepared</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared" v-model="prhead.date_prepared">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Issued</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Issued" v-model="prhead.date_issued">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Department</label>
										<select class="form-control" v-model="prhead.department_id" @change="generatePrNo()">
											<option value=''>--Select Department--</option>
											<option :value="dept.id" v-for="dept in department_list" :key="dept.id">{{ dept.department_name }}</option>
										</select>
										<!-- <input type="text" class="form-control" placeholder="Department" value="IT Department"> -->
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Urgency</label>
										<input type="text" class="form-control" placeholder="" v-model="prhead.urgency">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Process Code</label>
										<!-- <input type="text" class="form-control" placeholder="" v-model="prhead.process_code"> -->
										<select class="form-control" v-model="prhead.process_code">
											<option value=''>--Select Process Code--</option>
											<option :value="pro" v-for="pro in processing_code" :key="pro">{{ pro }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Requestor</label>
										<input type="text" class="form-control" placeholder="Requestor" v-model="prhead.requestor">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">End-Use</label>
										<input type="text" class="form-control" placeholder="End-Use" v-model="prhead.enduse">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Purpose</label>
										<input type="text" class="form-control" placeholder="Purpose" v-model="prhead.purpose">
									</div>
								</div>
							<!-- </div> -->
							</div>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mt-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="5%">#</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
											<td class="p-1 uppercase" width="15%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class=""><input placeholder="#" type="text" v-model="item_no" class="w-full p-1 text-center" disabled></td>
											<td class=""><input placeholder="Qty" type="text" v-model="qty" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="w-full p-1 text-center" id="check_qty"></td>
											<td class=""><input placeholder="UOM" type="text" v-model="uom" class="w-full p-1 text-center" id="check_uom"></td>
											<td class=""><input placeholder="PN No." type="text" v-model="pn_no" class="w-full p-1"></td>
											<td class=""><input placeholder="Item Description" v-model="item_desc" type="text" class="w-full p-1" id="check_description"></td>
											<td class=""><input placeholder="WH Stock" type="text" v-model="wh_stocks" class="w-full p-1" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" ></td>
											<td class=""><input placeholder="Date Needed" type="text" v-model="date_needed" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addItem">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(details,index) in prdetails">
											<td class="p-1 text-center">{{index+1}}</td>
											<td class="p-1 text-center">{{details.quantity}}</td>
											<td class="p-1 text-center">{{details.uom}}</td>
											<td class="p-1">{{details.pn_no}}</td>
											<td class="p-1">{{details.item_description}}</td>
											<td class="p-1">{{details.wh_stocks}}</td>
											<td class="p-1">{{details.date_needed}}</td>
											<td class="p-1">
												<input placeholder="Recom Date" type="text" v-model="recom_date_update[index]" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(details.id)" v-if="props.id==0 && (details.recom_date==null || details.recom_date=='')">

												<input @change="updateRecomdate(details.id)" placeholder="Recom Date" type="text" v-model="details.recom_date" class="w-full p-1" onfocus="(this.type='date')" v-else-if="props.id==0 && (details.recom_date!=null || details.recom_date!='')" readonly>

												<input placeholder="Recom Date" type="text" v-model="details.recom_date" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(details.id)" v-else>
											</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="deleteItem(details.id,'no')">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(i,indexes) in item_list">
											<td class="p-1 text-center">{{ indexes + 1 + prdetails.length }}</td>
											<td class="p-1 text-center">{{ i.qty }}</td>
											<td class="p-1 text-center">{{ i.uom }}</td>
											<td class="p-1">{{ i.pn_no }}</td>
											<td class="p-1">{{ i.item_desc }}</td>
											<td class="p-1">{{ i.wh_stocks }}</td>
											<td class="p-1">{{ i.date_needed }}</td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="i.recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeItem(indexes)">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="row">
								<div class="col-lg-12">
									<label for="" class="text-sm leading-none" >
										<input id="checkbox" type="checkbox" v-model="prhead.petty_cash" @click="showSigna()" true-value="1" false-value="0">
										Petty Cash
									</label>
								</div>
							</div>
							<!-- <div v-for="p in prhead"> -->
								<div id="showsigna" :style="(props.id!=0 && prhead.petty_cash==1) ? '' : 'display: none;'">
									<div class="row mt-2">
										<div class="col-lg-3 col-md-3">
											<div class="form-group">
												<label class="text-gray-500 m-0" for="">Date</label>
												<input type="date" class="form-control" placeholder="Date" v-model="approved_date" v-if="props.id==0">
												<input type="date" class="form-control" placeholder="Date" v-model="petty_cash.approved_date" v-else>
											</div>
										</div>
										<div class="col-lg-9 col-md-9">
											<div class="form-group">
												<label class="text-gray-500 m-0" for="">Comment</label>
												<textarea class="form-control" placeholder="Comment" v-model="remarks" rows="1" v-if="props.id==0"></textarea>
												<textarea class="form-control" placeholder="Comment" v-model="petty_cash.remarks" rows="1" v-else></textarea>
											</div>
										</div>
									</div>
									<div class="row mt-4 mb-4">
										<div class="col-lg-12">
											<table class="w-full text-xs">
												<tr>
													<td class="text-center" width="20%">Prepared by</td>
													<td width="2%"></td>
													<td class="text-center" width="20%">Recommending Approval</td>
													<td width="2%"></td>
													<td class="text-center" width="20%">Approved by</td>
												</tr>
												<tr>
													<td class="text-center border-b"><br></td>
													<td></td>
													<td class="text-center border-b"></td>
													<td></td>
													<td class="text-center border-b"></td>
												</tr>
												<tr>
													<td class="text-center p-0">
														<select class="p-1 text-center w-full bg-yellow-50" v-model="prepared_by" v-if="props.id==0">
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
														<select class="p-1 text-center w-full bg-yellow-50" v-model="petty_cash.prepared_by" v-else>
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
													</td>
													<td></td>
													<td class="text-center p-0">
														<select class="p-1 text-center w-full bg-yellow-50" v-model="recommended_by" v-if="props.id==0">
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
														<select class="p-1 text-center w-full bg-yellow-50" v-model="petty_cash.recommended_by" v-else>
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
													</td>
													<td></td>
													<td class="text-center p-0">
														<select class="p-1 text-center w-full bg-yellow-50" v-model="approved_by" v-if="props.id==0">
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
														<select class="p-1 text-center w-full bg-yellow-50" v-model="petty_cash.approved_by" v-else>
															<option value=''>--Select Signatory--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
													</td>
												</tr>
												<tr>
													<td class="text-center"><br><br></td>
													<td></td>
													<td class="text-center"></td>
													<td></td>
													<td class="text-center"></td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							<!-- </div> -->
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<!-- <button class="btn btn-light">Cancel</button> -->
										<!-- <button type="submit" class="btn btn-danger mr-2 w-36" v-on:click="pr_options = ''">Cancel</button> -->
										<button type="button" class="btn btn-danger mr-2 w-36" @click="cancelPr('no')">Cancel</button>
										<button type="button" class="btn btn-warning text-white mr-2 w-36" @click="onSaveDraftUpload()">Save as Draft</button>
										<button type="button" class="btn btn-primary mr-2 w-44" @click="onSave()">Save</button>
										<!-- <button type="submit" class="btn btn-primary mr-2 w-44" @click="openSuccessAlert()">Save</button> -->
									</div>
								</div>
							</div>
						</div>
						<div class="" id="manual" v-else-if="pr_options === 'pr_manual'">
							<hr class="border-dashed">
							<p class="text-gray-500 font-bold text-lg">Add Manual PR</p>
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Location</label>
										<input type="text" class="form-control" placeholder="Location" v-model="form.purchase_request">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">PR No</label>
										<input type="text" class="form-control" placeholder="PR No" v-model="form.pr_no" readonly>
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Site PR No</label>
										<input type="text" class="form-control" placeholder="Site PR No" v-model="form.site_pr">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Prepared</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared" v-model="form.date_prepared">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Issued</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Issued" v-model="form.date_issued">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Department</label>
										<select class="form-control" v-model="form.department" @change="generatePrNo()">
											<option value=''>--Select Department--</option>
											<option :value="dept.id" v-for="dept in department_list" :key="dept.id">{{ dept.department_name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Urgency</label>
										<input type="text" class="form-control" placeholder="Urgency" v-model="form.urgency" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Process Code</label>
										<!-- <input type="text" class="form-control" placeholder="Process Code"> -->
										<select class="form-control" v-model="form.process_code">
											<option value=''>--Select Process Code--</option>
											<option :value="pro" v-for="pro in processing_code" :key="pro">{{ pro }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Requestor</label>
										<input type="text" class="form-control" placeholder="Requestor" v-model="form.requestor">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">End-Use</label>
										<input type="text" class="form-control" placeholder="End-Use" v-model="form.enduse">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Purpose</label>
										<input type="text" class="form-control" placeholder="Purpose" v-model="form.purpose">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mt-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="5%">#</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
											<td class="p-1 uppercase" width="15%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class=""><input placeholder="#" type="text" v-model="item_no" class="w-full p-1 text-center" disabled></td>
											<td class=""><input placeholder="Qty" type="text" v-model="qty" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="w-full p-1 text-center" id="check_qty"></td>
											<td class=""><input placeholder="UOM" type="text" v-model="uom" class="w-full p-1 text-center" id="check_uom"></td>
											<td class=""><input placeholder="PN No." type="text" v-model="pn_no" class="w-full p-1"></td>
											<td class=""><input placeholder="Item Description" v-model="item_desc" type="text" class="w-full p-1" id="check_description"></td>
											<td class=""><input placeholder="WH Stock" type="text" v-model="wh_stocks" class="w-full p-1" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"></td>
											<td class=""><input placeholder="Date Needed" type="text" v-model="date_needed" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addItem">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										
										<tr v-for="(i,index) in item_list">
											<td class="p-1 text-center">{{ index+1 }}</td>
											<td class="p-1 text-center">{{ i.qty }}</td>
											<td class="p-1 text-center">{{ i.uom }}</td>
											<td class="p-1">{{ i.pn_no }}</td>
											<td class="p-1">{{ i.item_desc }}</td>
											<td class="p-1">{{ i.wh_stocks }}</td>
											<td class="p-1">{{ i.date_needed }}</td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="i.recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeItem(index)">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="row">
								<div class="col-lg-12">
									<label for="" class="text-sm leading-none">
										<input id="checkbox" type="checkbox" @click="showSigna()" v-model="form.petty_cash" true-value="1" false-value="0">
										Petty Cash
									</label>
								</div>
							</div>
							<div id="showsigna" style="display: none;">
								<div class="row mt-2">
									<div class="col-lg-3 col-md-3">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Date</label>
											<input type="date" class="form-control" placeholder="Date" v-model="approved_date">
										</div>
									</div>
									<div class="col-lg-9 col-md-9">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Comment</label>
											<textarea class="form-control" placeholder="Comment" v-model="remarks" rows="1"></textarea>
										</div>
									</div>
								</div>
								<div class="row mt-4 mb-4">
									<div class="col-lg-12">
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="20%">Prepared by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Recommending Approval</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Approved by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br></td>
												<td></td>
												<td class="text-center border-b"></td>
												<td></td>
												<td class="text-center border-b"></td>
											</tr>
											<tr>
												<td class="text-center p-0">
													<select class="p-1 text-center w-full bg-yellow-50" v-model="prepared_by">
														<option value=''>--Select Signatory--</option>
														<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
													</select>
												</td>
												<td></td>
												<td class="text-center p-0">
													<select class="p-1 text-center w-full bg-yellow-50" v-model="recommended_by">
														<option value=''>--Select Signatory--</option>
														<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
													</select>
												</td>
												<td></td>
												<td class="text-center p-0">
													<select class="p-1 text-center w-full bg-yellow-50" v-model="approved_by">
														<option value=''>--Select Signatory--</option>
														<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
													</select>
												</td>
											</tr>
											<tr>
												<td class="text-center"><br><br></td>
												<td></td>
												<td class="text-center"></td>
												<td></td>
												<td class="text-center"></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<!-- <button class="btn btn-light">Cancel</button> -->
										<!-- <button type="button" class="btn btn-danger mr-2 w-36" @click="openDangerAlert()">Cancel</button> -->
										<button type="button" id="btn_draft" class="btn btn-warning text-white mr-2 w-36" @click="onSaveDraftManual()">Save as Draft</button>
										<button type="button" class="btn btn-primary mr-2 w-44" @click="onSaveManual()">Save</button>
										<!-- <a href="" class="btn btn-primary mr-2 w-52">Save & Print Petty Cash</a> -->
									</div>
								</div>
							</div>
						</div>
						<div v-else></div>
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
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">

									<a href="/pur_req/new/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a :href="'/pur_quote/new/'+pr_head_id" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" v-if="prheadid==0">Proceed</a>
									<a :href="'/pur_quote/new/'+prheadid" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" v-else>Proceed</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertCD }">
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
			<div class="modal p-0 !bg-transparent" :class="{ show:warningAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-yellow-400 border-8 bg-yellow-400 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-yellow-400">Success!</h2>
									<h5 class="leading-tight">You have successfully saved a PR as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_req" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<a href="/pur_req/new/0" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to cancel this PR?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="cancelPr('yes')">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlerterrors }">
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
									<h5 class="leading-tight" v-if="error!=''" >{{ error }}</h5>
									<h5 class="leading-tight" v-else-if="error_inventory!=''">{{ error_inventory }}</h5>
									<h5 class="leading-tight" v-else-if="error_pr!=''" v-for="er in error_pr">{{ er }}</h5>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item1 }">
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
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteItem(pr_details_id,'yes')" >Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item2 }">
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
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="removeItem2(index)">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>