<p>Dear {{ $name }},</p>
<p>This mail was generated automatically<br>
	Please click <a href="{{ env('APP_URL')}}">here</a> and login to the your account with following credential.</p>
<p>email: {{ $email }}
	<br>
	password: {{ $password }}
</p>
<p>Best Regards,
	<br>
	{{ env('APP_NAME') }}
</p>