( function ( $ ) {

	var charts = {
		init: function () {
			// -- Set new default font family and font color to mimic Bootstrap's default styling
			Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
			Chart.defaults.global.defaultFontColor = '#292b2c';
			
			this.ajaxGetTreeRemovalsMonthlyData();
			this.ajaxGetRestorationsMonthlyData();
			this.ajaxGetProcessItemsTypeData();
		},
		
		
		ajaxGetTreeRemovalsMonthlyData: function () {
			var request = $.ajax({
				method: 'GET',
				url: 'get-treeRemoval-chart-data'
			});

			request.done(function (response) {
				console.log(response);
				charts.createTreeRemovalsChart(response);
			});
		},

		/**
		 * Created the Tree Removals Line Chart
		 */
		createTreeRemovalsChart: function (response) {

			var ctx = document.getElementById("TreeRemovalAreaChart");
			var myLineChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: response.months_years, // The response got from the ajax request containing all month names in the database
					datasets: [{
						label: "Tree Removals (no. of Trees)",
						lineTension: 0.3,
						backgroundColor: "#A46CB8",
						borderColor: "#A46CB8",
						pointRadius: 5,
						pointBackgroundColor: "#A46CB8",
						pointBorderColor: "#A46CB8",
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "#A46CB8",
						pointHitRadius: 20,
						pointBorderWidth: 2,
						data: response.tree_removal_count_data // The response got from the ajax request containing data for the completed jobs in the corresponding months
					}],
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'date'
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 7
							}
						}],
						yAxes: [{
							label: "No. of Tree Removals Requested for",
							ticks: {
								min: 0,
								max: response.max, // The response got from the ajax request containing max limit for y axis
								maxTicksLimit: 5
							},
							gridLines: {
								color: "rgba(0, 0, 0, .125)",
							}
						}],
					},
					legend: {
						display: true
					}
				}
			});
		},


		ajaxGetRestorationsMonthlyData: function () {
			var request = $.ajax({
				method: 'GET',
				url: 'get-restoration-chart-data'
			});

			request.done(function (response) {
				console.log(response);
				charts.createRestorationsChart(response);
			});
		},

		/**
		 * Created the Restorations Line Chart
		 */
		createRestorationsChart: function (response) {

			var ctx = document.getElementById("RestorationAreaChart");
			var myLineChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: response.resto_months_years, // The response got from the ajax request containing all month names in the database
					datasets: [{
						label: "Trees Restored",
						lineTension: 0.3,
						backgroundColor: "#A46CB8",
						borderColor:"#A46CB8",
						pointRadius: 5,
						pointBackgroundColor: "#A46CB8",
						pointBorderColor: "#A46CB8",
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "#A46CB8",
						pointHitRadius: 20,
						pointBorderWidth: 2,
						data: response.restoration_count_data // The response got from the ajax request containing data for the environment restorations in the corresponding months
					}],
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'date'
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 7
							}
						}],
						yAxes: [{
							label: "No. of trees requested to be restored",
							ticks: {
								min: 0,
								max: response.max, // The response got from the ajax request containing max limit for y axis
								maxTicksLimit: 5
							},
							gridLines: {
								color: "rgba(0, 0, 0, .125)",
							}
						}],
					},
					legend: {
						display: true
					}
				}
			});
		},

		ajaxGetProcessItemsTypeData: function () {
			var request = $.ajax( {
				method: 'GET',
				url: 'get-processItem-formType-chart-data'
			} );

			request.done( function ( response ) {
				console.log( response );
				charts.createProcessItemsTypesChart( response );
			});
		},


		/**
		 * Created the Process Items Types bar Chart
		 */
		createProcessItemsTypesChart: function ( response ) {

			var ctx = document.getElementById("processItemTypeChart");
			var myLineChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: response.form_type, // The response got from the ajax request containing all month names in the database
					datasets: [{
						label: "Requests",
						lineTension: 0.3,
						backgroundColor: "#A46CB8",	
						borderColor: "#A46CB8",
						pointRadius: 5,
						pointBackgroundColor: "#A46CB8",
						pointBorderColor: "#A46CB8",
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "#A46CB8",
						pointHitRadius: 20,
						pointBorderWidth: 2,
						data: response.process_item_type_count_data // The response got from the ajax request containing data 
					}],
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'date'
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 7
							}
						}],
						yAxes: [{
							label:"No. of Process Items",
							ticks: {
								min: 0,
								max: response.max, // The response got from the ajax request containing max limit for y axis
								maxTicksLimit: 5
							},
							gridLines: {
								color: "rgba(0, 0, 0, .125)",
							}
						}],
					},
					title:{
						display:true,
						text:'NUMBER OF REQUESTS MADE OF EACH TYPE',
						position:'top'
					},
					legend: {
						display: true
					},
					responsive: {  
						rules: [{  
						  condition: {  
							maxWidth: 500  
						  },  
						  chartOptions: {  
							legend: {  
							  enabled: false  
							}  
						  }  
						}]  
					  }
				}
			})
			;
		},
	


		
	};

	charts.init();

} )( jQuery );