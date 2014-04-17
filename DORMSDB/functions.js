function O(obj)
{
	if (typeof obj == 'object') return obj
	else return document.getElementById(obj)
}

function S(obj)
{
	return O(obj).style
}

function C(name)
{
	var elements = document.getElementsByTagName('*')
	var objects = []

	for (var i=0; i < elements.length ; ++i)
		if (elements[i].classname == name)
			objects.push(elements[i])
	return objects
}

function checkEmail(email)
{
	if (email.value == '')
	{
		O('emailcheck').innerHTML= ''
		return
	}
	params = "email=" + email.value
	request = new ajaxRequest()
	request.open("POST", "checkdb.php", true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.setRequestHeader("Content-length", params.length)
	request.setRequestHeader("Connection", "close")

	request.onreadystatechange = function()
	{
		if (this.readyState == 4)
			if(this.status == 200)
				if(this.responseText != null)
					O('emailcheck').innerHTML= this.responseText
	}
	request.send(params)
}



function ajaxRequest()
{
	try { var request = new XMLHttpRequest() }
	catch (e1){
		try { request = new ActiveXObject("Msxml2.XMLHTTP") }
		catch (e2) {
			try { request = new ActiveXObject("Microsoft.XMLHTTP") }
			catch (e3) {
				request = false
				}
			}
		}
	return request
}

// Thanks to Robin Nixon for the previous functions as detailed in "PHP, MySQL, Javascript, & CSS: 2nd Edition" (O'Reilly).

function checkName(name,field)
{
	if (name.value == '')
	{
		O(field).innerHTML= ''
		return
	}
	else if (/[^a-zA-Z-'" "]/.test(name.value))
		O(field).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Names cannot include non-alphabetic characters.</span>"
	else O(field).innerHTML= "<span class= 'acceptable'>&nbsp;&#x2714; </span> "
}


function checkGradyear(year)
{
	if (year.value == '')
	{
		O(gyearcheck).innerHTML= ''
		return
	}
	else if (/[^0-9']/.test(year.value))
		O(gyearcheck).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Graduation year must be a four digit number. </span>"
	else O(gyearcheck).innerHTML= "<span class= 'acceptable'>&nbsp;&#x2714; </span> "
}

function checkPassword(password)
{
	if (password.value == '')
	{
		O(pwordcheck).innerHTML=''
		return
	}
	else if (password.value.length < 8)
		O(pwordcheck).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Passwords must be at least 8 characters long."
	else if (!/[a-z]/.test(password.value) || ! /[A-Z]/.test(password.value) || !/[0-9]/.test(password.value))
		O(pwordcheck).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Must contain at least one lowercase, uppercase, and number.</span>"
	else O(pwordcheck).innerHTML= "<span class= 'acceptable'>&nbsp;&#x2714; </span> "
}

function verifyPassword(password, vpassword)
{
	if (vpassword.value == '')
	{
		O(vpasscheck).innerHTML=''
		return
	}
	else if (O(password).value === vpassword.value)
		O(vpasscheck).innerHTML= "<span class= 'acceptable'>&nbsp;&#x2714; Passwords match.</span> "
	else O(vpasscheck).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Passwords do not match.</span>"
}

function validateEmail(email)
{
	if (email.value == '')
	{
		O(emailcheck).innerHTML=''
		return
	}
	else if (!/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email.value))
		O(emailcheck).innerHTML= "<span class='unacceptable'>&nbsp;&#x2718; Not a valid Email Address.</span>";

	else O(emailcheck).innerHTML= "<span class= 'acceptable'>&nbsp;&#x2714;</span>";
}
