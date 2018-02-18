
		//function to build a calander on our page using a month object
			function buildCal(month){
				//get the number of weeks in the month so we can make an approriately sized calendar
				var numWeeks = month.getWeeks().length;
				
				//get the number of days in the month from the array we made earlier
				var numDays = monthLength[month.month];
				//account for leap years if necessary
				if(month.month==1 && month.year%4===0 && month.year%100!==0){
					numDays=29;
				}
				
				//get the day of the week that the first of the month is on
				var startDayWeek = month.getDateObject(1).getDay();
				
				//update the display on the page to display the month and year of the calendar
				document.getElementById("dispMon").innerHTML=monthNames[month.month]+" "+month.year;
				var table = document.getElementById("calendar");
				
				//reset variables dayNum(counter used to label cells of the table with the day of the month) and started(used for stuff)
				var dayNum = 1;
				var started = false;
				
				//actually make the calendar now
				for (i = 0; i <numWeeks; i++){
					//insert a new row at the bottom of the calendar
					var r=i+1;
					var row = table.insertRow(r);
					
					//make cells for each day of the week in the row
					for (j = 0; j<7; j++){
						var cell = row.insertCell(j);
						//if the month has "started" but not "ended", but day numbers into the appropriate cells
						if(dayNum<=numDays && (j===Number(startDayWeek) || started===true)){
							//put day number into the cell
							cell.innerHTML=dayNum;
							var cellID=dayNum;
							cell.id=cellID;
							cell.className="day";
							started = true;
							//increment the day number
							dayNum++;
						}
					}
				}
			}
			
		//function to build a calendar for the previous month
			function decreaseMonth()
			{
				//delete rows from the caldendar so that only the header with weekday names remains
				wksRemove=currentMonth.getWeeks().length;
				var table = document.getElementById("calendar");
				for (i=0; i<wksRemove;i++){
					table.deleteRow(1);
				}
				
				//decrement the month
				currentMonth = currentMonth.prevMonth();
				//build the new calendar
				buildCal(currentMonth);
			}
			
		//function to build a calendar for the next month
			function increaseMonth()
			{
				//delete rows from the caldendar so that only the header with weekday names remains
				wksRemove=currentMonth.getWeeks().length;
				var table = document.getElementById("calendar");
				for (i=0; i<wksRemove;i++){
					table.deleteRow(1);
				}
				
				//increment the month
				currentMonth = currentMonth.nextMonth();
				//build the calendar based on this new month
				buildCal(currentMonth);
			}