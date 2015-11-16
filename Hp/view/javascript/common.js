function delconfirm() {
confirm('Are You Sure Delete');
}
function check(field,div)
	{
		var str=document.getElementById(field).value;
		str   =   str.replace(/^\s+|\s+$/g,"");   //去掉空格
	var userCheck=str;
	var divcheck=document.getElementById(div);
	var bnt=document.getElementById('btnSubmit');

			if(userCheck==""||userCheck==null)
		{
			 document.getElementById(field).className='nbt';
			 divcheck.innerHTML=" <font color=red>Empty</font>";
			 bnt.disabled=true;
		 }else{
			 document.getElementById(field).className='';
			 divcheck.innerHTML="<img src=templates/admin/images/checked.gif width=14 height=14>";
		      bnt.disabled=false;
}
	
	}

function checkdata(field,div)   
	{
		var str=document.getElementById(field).value;
		str   =   str.replace(/^\s+|\s+$/g,"");   //去掉空格
	var userCheck=str;
	var divcheck=document.getElementById(div);
	var bnt=document.getElementById('btnSubmit');
			if(userCheck==""||userCheck==null)
		{
			 document.getElementById(field).className='nbt';
			 divcheck.innerHTML=" <font color=red>Empty</font>";
			 bnt.disabled=true;
		 }else if(!fucCheckNUM(userCheck))
		     {
			 document.getElementById(field).className='nbt';
			 divcheck.innerHTML=" <font color=red>Not Numeral </font>";
			 bnt.disabled=true; 
			 
			 }
		 else{
			 document.getElementById(field).className='';
			 divcheck.innerHTML="<img src=templates/admin/images/checked.gif width=14 height=14>";
		      bnt.disabled=false;


}
	
	}
    //检查是否非空
    function notEmpty(obj, msg)
    {
        str = obj.value;
        str1 = "";
        for (i = 0; i < str.length; i++)
        {
                if (str.charAt(i) != " ")
                {
                    str1 = str.substr(i, str.length);
                    break;
                }
        }
    
        if (str1 == "")
        {
            alert(msg);
            obj.value = "";
            obj.focus();
            return false;
        }
        else
        {
            return true;
        }
    }
    
    //检查是否为数字
function fucCheckNUM(NUM) 
{ 
var i,j,strTemp; 
strTemp="0123456789"; 
if ( NUM.length== 0) 
return 0 
for (i=0;i<NUM.length;i++) 
{ 
j=strTemp.indexOf(NUM.charAt(i)); 
if (j==-1) 
{ 
//说明有字符不是数字 
return false; 
} 
} 
//说明是数字 
return true; 
} 
    //检查密码是否相同
    function isSamePwd(objPwd1, objPwd2, msg)
    {
        pwd1 = objPwd1.value;
        pwd2 = objPwd2.value;
    
        if (pwd1 != pwd2)
        {
        if (null == msg)
        {
            alert("密码不相同！");
         }
         else
         {
             alert(msg);
         }
         
        objPwd2.value = "";
        objPwd2.focus();
        return false;
        }
        else
        {
        return true;
        }
    }
    
    //检查邮件地址
    function isEmail(obj, msg)
    {
        ch = obj.value;
        if((ch.indexOf("@") < 1) || (ch.indexOf(".") < 1) || (ch.indexOf(".") == ch.length - 1))
        {
        if (null == msg)
        {
            alert("Email Error！");
        }
        else
        {
            alert(msg);
        }
        obj.select();
        return false;
        }
        else
        {
        return true;
        }
    }

		function CheckAll(form){
	for (var i=0;i<form.elements.length;i++){
		var e = form.elements[i];
		e.checked == true ? e.checked = true : e.checked = true;
	}
	}
		function CheckAlls(form){
	for (var i=0;i<form.elements.length;i++){
		var e = form.elements[i];
		e.checked == false ? e.checked = false : e.checked = false;
	}
	}
