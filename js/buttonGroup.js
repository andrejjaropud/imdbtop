function validatedate(inputText,val_max,val_min)
{
    var dateformat = /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/;
    // Match the date format through regular expression
    if(inputText.match(dateformat))
    {

        //Test which seperator is used '/' or '-'
        var opera2 = inputText.split('-');
        lopera2 = opera2.length
        // Extract the string into month, date and year
        if (lopera2>1)
        {
            var pdate = inputText.split('-');
        }
        else
        {
            alert('Invalid date format!');
            $( "#datepicker" ).focus();
            return false;
        }

        var yy  = parseInt(pdate[0]);
        var mm = parseInt(pdate[1]);
        var dd = parseInt(pdate[2]);
        // Create list of days of a month [assume there is no leap year by default]
        var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];
        if (mm==1 || mm>2)
        {
            if (dd>ListofDays[mm-1])
            {
                $( "#datepicker" ).focus();
                alert('Invalid date format!');
                return false;
            }
        }
        if (mm==2)
        {
            var lyear = false;
            if ( (!(yy % 4) && yy % 100) || !(yy % 400))
            {
                lyear = true;
            }
            if ((lyear==false) && (dd>=29))
            {
                $( "#datepicker" ).focus();
                alert('Invalid date format!');
                return false;
            }
            if ((lyear==true) && (dd>29))
            {
                $( "#datepicker" ).focus();
                alert('Invalid date format!');
                return false;
            }
        }
        if (new Date(inputText).getTime() >= new Date(val_min).getTime() && new Date(inputText).getTime() <= new Date(val_max).getTime())
        {
            return true;
        }
        else
        {
            alert("The Top for this date is absent!\n There are Tops from "+val_min+" to "+val_max);
            $( "#datepicker" ).focus();
            return false;
        }
    }
    else
    {
        alert("Invalid date format!");
        $( "#datepicker" ).focus();
        return false;
    }
}
