/* ==================================================
Formatted Date
This function (datef) will behave similarly to PHP's date function.
Pass it a format string and a valid Javascript Date object and it will
generate a neatly formatted date.

The following characters will be converted:
d - Day of the month, 2 digits with leading zeros
D - A textual representation of a day, three letters
j - Day of the month without leading zeros
l - A full textual representation of the day of the week (lowercase 'L')
N - numeric representation of the day of the week, 1 (for Monday) through 7 (for Sunday)
S - English ordinal suffix for the day of the month, 2 characters
w - Numeric representation of the day of the week, 0 (for Sunday) through 6 (for Saturday)
z - The day of the year (starting from 0)
F - A full textual representation of a month, such as January or March
m - Numeric representation of a month, with leading zeros
M - A short textual representation of a month, three letters
n - Numeric representation of a month, without leading zeros
t - Number of days in the given month
Y - A full numeric representation of a year, 4 digits
y - A two digit representation of a year
a - Lowercase Ante meridiem and Post meridiem
A - Uppercase Ante meridiem and Post meridiem
g - 12-hour format of an hour without leading zeros
G - 24-hour format of an hour without leading zeros
h - 12-hour format of an hour with leading zeros
H - 24-hour format of an hour with leading zeros
i - Minutes with leading zeros
s - Seconds, with leading zeros

Any other characters will be passed along in the string unmodified

================================================== */

var aryDateText = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
var aryDateTextShort = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
var aryMonths = ["January","February","March","April","May","June",
                 "July","August","September","October","November","December"];
var aryMonthsShort = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var aryDaysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31];
var aryDaysInMonthLeap = [31,29,31,30,31,30,31,31,30,31,30,31];
var aryHours12 = [12,1,2,3,4,5,6,7,8,9,10,11,12,1,2,3,4,5,6,7,8,9,10,11];

function datef(format,date) {
  if (format) {
    var dateFormatted = "";
    for (var i=0; i<format.length; i++ ) {
      if (format[i] == "d") { //Day of the month, 2 digits with leading zeros
        var dateDD = date.getDate();
        if (dateDD < 10)
          dateFormatted += "0";
        dateFormatted += dateDD;
      } else if (format[i] == "D") { //A textual representation of a day, three letters
        dateFormatted += aryDateTextShort[date.getDay()];
      } else if (format[i] == "j") { //Day of the month without leading zeros
        dateFormatted += date.getDate();
      } else if (format[i] == "l") { //A full textual representation of the day of the week (lowercase 'L')
        dateFormatted += aryDateText[date.getDay()];
      } else if (format[i] == "N") { //numeric representation of the day of the week, 1 (for Monday) through 7 (for Sunday)
        var dateDD = date.getDay();
        if (dateDD == 0) dateDD = 7;
        dateFormatted += dateDD;
      } else if (format[i] == "S") { //English ordinal suffix for the day of the month, 2 characters
        var dateDD = date.getDate();
        if (dateDD == 1)
          dateFormatted += "st";
        else if (dateDD == 2)
          dateFormatted += "nd";
        else if (dateDD == 3)
          dateFormatted += "rd";
        else if (dateDD == 21)
          dateFormatted += "st";
        else if (dateDD == 22)
          dateFormatted += "nd";
        else if (dateDD == 23)
          dateFormatted += "rd";
        else if (dateDD == 31)
          dateFormatted += "st";
        else
          dateFormatted += "th";
      } else if (format[i] == "w") { //Numeric representation of the day of the week, 0 (for Sunday) through 6 (for Saturday)
        dateFormatted += date.getDay();
      } else if (format[i] == "z") { //The day of the year (starting from 0)
        var day1 = new Date("1/1/"+date.getFullYear());
        var daysSinceDay1 = Math.floor((date.getTime() - day1.getTime()) / (1000*60*60*24)) - 1;
        dateFormatted += daysSinceDay1;
      } else if (format[i] == "F") { //A full textual representation of a month, such as January or March
        dateFormatted += aryMonths[date.getMonth()];
      } else if (format[i] == "m") { //Numeric representation of a month, with leading zeros
        var monthMM = date.getMonth()+1;
        if (monthMM < 10)
          dateFormatted += "0";
        dateFormatted += monthMM;
      } else if (format[i] == "M") { //A short textual representation of a month, three letters
        dateFormatted += aryMonthsShort[date.getMonth()];
      } else if (format[i] == "n") { //Numeric representation of a month, without leading zeros
        dateFormatted += date.getMonth()+1;
      } else if (format[i] == "t") { //Number of days in the given month
        if (isLeapYear(date))
          dateFormatted += aryDaysInMonthLeap[date.getMonth()];
        else
          dateFormatted += aryDaysInMonth[date.getMonth()];
      } else if (format[i] == "Y") { //A full numeric representation of a year, 4 digits
        dateFormatted += date.getFullYear();
      } else if (format[i] == "y") { //A two digit representation of a year
        dateFormatted += String(date.getFullYear()).substring(2,4);
      } else if (format[i] == "a") { //Lowercase Ante meridiem and Post meridiem
        if (date.getHours() > 11)
          dateFormatted += "pm";
        else
          dateFormatted += "am";
      } else if (format[i] == "A") { //Uppercase Ante meridiem and Post meridiem
        if (date.getHours() > 11)
          dateFormatted += "PM";
        else
          dateFormatted += "AM";
      } else if (format[i] == "g") { //12-hour format of an hour without leading zeros
        dateFormatted += aryHours12[date.getHours()];
      } else if (format[i] == "G") { //24-hour format of an hour without leading zeros
        dateFormatted += date.getHours();
      } else if (format[i] == "h") { //12-hour format of an hour with leading zeros
        var dateHH = date.getHours();
        if (dateHH == 0)
          dateHH = 12;
        else if (dateHH > 12)
          dateHH = dateHH - 12;
        if (dateHH < 10)
          dateFormatted += "0";
        dateFormatted += dateHH;
      } else if (format[i] == "H") { //24-hour format of an hour with leading zeros
        var dateHH = date.getHours();
        if (dateHH < 10)
          dateFormatted += "0";
        dateFormatted += dateHH;
      } else if (format[i] == "i") { //Minutes with leading zeros
        var dateMM = date.getMinutes();
        if (dateMM < 10)
          dateFormatted += "0";
        dateFormatted += dateMM;
      } else if (format[i] == "s") { //Seconds, with leading zeros
        var dateSS = date.getSeconds();
        if (dateSS < 10)
          dateFormatted += "0";
        dateFormatted += dateSS;
      } else {
        dateFormatted += format[i];
      }
    }
    return dateFormatted;
  } else {
    return date.toString();
  }
}

function isLeapYear(date) {
  if ((date.getFullYear()/400) == Math.floor(date.getFullYear()/400))
    return true;
  else if ((date.getFullYear()/100) == Math.floor(date.getFullYear()/100))
    return false;
  else if ((date.getFullYear()/4) == Math.floor(date.getFullYear()/4))
    return true;
}