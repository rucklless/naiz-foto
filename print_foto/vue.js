var vm = new Vue({
	el: '#app',
	data: {
		test: 'test',
		files: '',
		fileList: [],
		countFiles: 0,
		currentUploadFiles: 0,
		displayCurrentUploadFiles:false,
		checkShowPrice:false,
		payment:false,
		form:{
			name: '',
			family: '',
			phone: '',
			mail: '',
			comment: '',
			order_accept: false,
			agreement: false
		},
		apply_to_all_check:false,
		show_order_check:false,
		type:{
			1: {
				id:1,
				val:'Матовая'
			},
			2: {
				id:2,
				val:'Глянцевая'
			},
		},
		all:{
			sizes: 1,
			type:1,
			count:1,
			field: 1,
		},
		sizes:{
			1: {
				id:1,
				val: '10x15',
				count: 0,
				prices: {
					1: {
						name: '0-100',
						val: 0, //цена для количества от
						price: 6.90
					},
					2: {
						name: '100-300',
						val: 100,
						price: 6.40
					},
					3: {
						name: '300+',
						val: 300,
						price: 5.90
					}
				},
				price: 0
			},
			2: {
				id:2,
				val: '15x20',
				prices: {
					1: {
						val: 0,
						price: 17
					}
				},
				price: 0
			},
			3: {
				id:3,
				val: '20x30',
				prices: {
					1: {
						val:0,
						price:35
					}
				},
				price: 0
			},
			4: {
				id:4,
				val: '30x40',
				prices: {
					1: {
						val: 0,
						price: 70
					}
				},
				price: 0
			},
			5: {
				id:5,
				val: '30x45',
				prices: {
					1: {
						val: 0,
						price: 80
					}
				},
				price: 0
			},
			6: {
				id:6,
				val: '30x60',
				prices: {
					1: {
						val: 0,
						price: 110
					}
				},
				price: 0
			},
			7: {
				id:7,
				val: '30x90',
				prices: {
					1: {
						val: 0,
						price: 160
					}
				},
				price: 0
			},
		},
		//обьект наполняемый ценами в зависимости от количества
		priceList:{

		},
		field:{
			1: {
				id:1,
				val: 'Не обрезать поля'
			},
			2: {
				id:2,
				val: 'Обрезать поля'
			},
		},
		total: 0,
		totalCopy:0,
		json:{}
	},
	methods:{
		fileUpload(){
			let uploadedFiles = this.$refs.files.files;
			this.countFiles = uploadedFiles.length;
			for(var i = 0; i < this.countFiles; i++){
				//this.saveFile(uploadedFiles[i], i)
				setTimeout(this.saveFile, timeout, uploadedFiles[i], i);
			}
		},
		saveFile(file, cnt){
			let formData = new FormData();
			formData.append('file', file);
			axios.post( 'request.php',
				formData,
				{
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
			).then(res => {
				//push
				this.fileList.push({
					id:res.data.id,
					preview:res.data.preview,
					type: this.type[1].id,
					sizes: this.sizes[1].id,
					field: this.field[1].id,
					count: 1,
					price: this.sizes[1].prices[1].price,
					cost: this.sizes[1].prices[1].price
				})
				this.sizes[1].count += 1
				this.currentUploadFiles += 1;
			})
				.catch(
					function(){
					console.log('FAILURE!!');
					}
				);
		},
		delFile(index){
			this.fileList.splice(index, 1)
		},
		delAll() {
			this.fileList = []
		},
		applyForAll(){
			for(f in this.fileList){
				file = this.fileList[f];
				file.count = this.all.count;
				file.field = this.all.field;
				file.sizes = this.all.sizes;
				file.type = this.all.type;
			}

		},
		setJson(payload){
			this.json = payload
		},
		showPrice(){
			this.checkShowPrice = true;
		},
		hidePrice(){
			this.checkShowPrice = false;
		},
		submitForm(){
			if(this.form.order_accept === true && this.form.agreement === true){
			data = 'ORDER=Y';
			if(this.form.name !== ''){
				data += '&ORDER_PROP[ORDER_NAME]='+this.form.name;
			}
			if(this.form.family !== ''){
				data += '&ORDER_PROP[ORDER_LAST_NAME]='+this.form.family;
			}
			if(this.form.phone !== ''){
				data += '&ORDER_PROP[ORDER_PHONE]='+this.form.phone;
			}
			if(this.form.mail !== ''){
				data += '&ORDER_PROP[ORDER_EMAIL]='+this.form.mail;
			}
			if(this.form.comment !== ''){
				data += '&ORDER_PROP[ORDER_DESCRIPTION]='+this.form.comment;
			}
			data += '&ORDER_PROP[SYSTEM_COUNT_FOTO]='+this.totalCopy;
			data += '&ORDER_PROP[SYSTEM_COUNT_USER_FOTO]='+this.countFiles;
			data += '&ORDER_PROP[SYSTEM_SUMM]='+this.total;

			if(this.fileList.length>0){
				for(f in this.fileList){
					file = this.fileList[f]
					data += '&ORDER_PROP[FOTO]['+f+'][IMG]='+file.id;
					data += '&ORDER_PROP[FOTO]['+f+'][TYPE_PAPER]='+this.type[file.type].val;
					data += '&ORDER_PROP[FOTO]['+f+'][SIZE]='+this.sizes[file.sizes].val;
					data += '&ORDER_PROP[FOTO]['+f+'][FIELD]='+this.field[file.field].val;
					data += '&ORDER_PROP[FOTO]['+f+'][COUNT]='+file.count;
					data += '&ORDER_PROP[FOTO]['+f+'][PRICE]='+file.cost;
				}
			}

			axios({
				method: 'post',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				url: 'request.php',
				data: data
			})
				.then(res => {
					this.fileList = []
					this.form.comment = ''
					this.form.family = ''
					this.form.mail = ''
					this.form.name = ''
					this.form.phone = ''
					this.show_order_check = false
					this.countFiles = 0
					this.currentUploadFiles = 0
					this.paymentForm()
				})
				.catch(function (error) {
					console.log(error);
				});
			}
		},
		paymentForm(){
			data = 'PAYMENT=Y&SUMM='+this.total
			axios({
				method: 'post',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				url: 'request.php',
				data: data
			})
				.then(res => {
					$('#payment').html(res.data);
					this.payment = true
					console.log(res);
				})
				.catch(function (error) {
					console.log(error);
				});
		}
	},
	computed:{
		priceCalc:function(){
			var arr = {}

			//получаем обьект с указанием сколько элементов какой толщины и бумаги
			for(var i = 0; i < this.fileList.length; i++){
				size = this.fileList[i].sizes;

				if(arr[size] === undefined) {
					arr[size] = 0
				}
				arr[size] += this.fileList[i].count
			}

			//перебираем arr и устанавливаем цены
			for(c in arr){
				if(arr[c] !== undefined){
					for(p in this.sizes[c].prices){
						if(arr[c] >= this.sizes[c].prices[p].val){
							if(this.priceList[c] === undefined){
								this.priceList[c] = {};
							}
							this.priceList[c] = this.sizes[c].prices[p].price;
						}else{
							break;
						}
					}
				}
			}

			//вписываем цены со скидкой от обьема считаем итого
			this.total = 0;
			this.totalCopy = 0;
			for(f in this.fileList){
				file = this.fileList[f]
				file.price = this.priceList[file.sizes]
				file.cost = file.count*file.price
				file.cost = file.cost.toFixed(2)*1
				//file.cost = Math.ceil((file.count*file.price)*100)/100
				this.total += file.cost
				this.totalCopy += file.count;
			}
			this.total = this.total.toFixed(2)*1
			return arr;
		},
		showCurrent(){
			if(this.countFiles != this.currentUploadFiles){
				this.displayCurrentUploadFiles = true;
			}else{
				this.displayCurrentUploadFiles = false;
			}
		}
	},
	watch:{
		priceCalc(){},
		costCalc(){}

	}
});