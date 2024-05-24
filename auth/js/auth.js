$(document).ready(function() {
    var App = {
        canvas: $("#canvas"),
        api: Config.url + "/api",
        path: Config.url + "/humano",
        token: localStorage.getItem("Token"),
        username: localStorage.getItem("Username"),
        authenticate: function() {
            if (App.token === 0 || App.token === null && App.username === 0 || App.username === null) {
                window.location.href = "/auth/#/login/";
            }else{
                $.ajax({
                    type: "POST",
                    url: App.api + "/system/tokens/verify",
                    dataType: "json",
                    data: {
                        token: App.token
                    },
                    success: function(data) {
                        if (parseInt(data.verified) === 0) {
                            window.location.href = "/auth/#/login/";
                        }
						else if(parseInt(data.verified) === 1) {
							if(data.token === App.token) {
								if(App.token !== "") {
									if(data.token === App.token) {
										window.history.back();
									}
									else {
										window.location.href = "/auth/#/login/";
									}
								}
								else {
									window.location.href = "/auth/#/login/";
								}
							}
							else {
								window.location.href = "/auth/#/login/";
							}
						}
						else {
							if (App.token === 0 || App.token === null && App.username === 0 || App.username === null) {
								window.location.href = "../auth/#/login/";
							}
						}
                    }
                });
            }
        },
        initialize: function() {
            App.authenticate();
            App.parent = App.api.replace("api/", "");
        },
    }

    //console.log(Config.url);

    App.initialize();

    $.Mustache.options.warnOnMissingTemplates = true;

    function hasher(location) {
        window.location.hash = location;
    }

    function clearPanel() {
        App.canvas.hide().fadeIn(200);
    }

    $.Mustache.load('templates/auth.html').done(function() {
        var canvas = $(App.canvas);

        $("#btnNew").click(function(e) {
            e.preventDefault();
            hasher("#/create/");
        });

        $("#employeeCancel").click(function(e) {
            e.preventDefault();
            hasher("#/login/");
        });

        Path.map("#/login/").to(function() {
            App.canvas.html("").append($.Mustache.render('login'));
            // localStorage.clear();
			
			$("#humano-module").hide();
			
			$("#username").change(function(e) {
				e.preventDefault();
				
				var username = $("input[name=username]").val();
				
				$.ajax({
                    type: "POST",
                    url: App.api + "/users/verified",
                    dataType: "json",
					data: {
						username: username
					},
                    success: function(data) {
						//console.log(data);
						if(parseInt(data.verified) === 1) {
							$("#humano-module").show();
						}
						else{
							$("#humano-module").hide();
						}
					}
				});
			});

            $("#loginForm").submit(function(e) {
                e.preventDefault();

                var username = $("input[name=username]").val();
                var password = $("input[name=password]").val();
                var gresponse = $("#g-recaptcha-response").val();
                var secretKey = "6LfG2NoSAAAAAEfKzFVtrIJ8b_MuAQbRVGWrOmOG";
              
                gresponse = true; // Edit this
              	if(gresponse) {

                $.ajax({
                    type: "POST",
                    url: App.api + "/users/authenticate",
                    dataType: "json",
                    data: {
                        username: username,
                        password: password,
                        secret: secretKey,
                        gresponse: gresponse
                    },
                    success: function(data) {
                        //console.log(data.verified);
						switch (parseInt(data.verified)) {
							case 1:
								localStorage.clear();
								localStorage.setItem("Token", data.Token);
								localStorage.setItem("Username", data.Username);
								localStorage.setItem("userType", data.Type);
								var humano_module = $("select[name=humano-module]").val();
								if (data.Type === "Administrator") {
									if(humano_module === "HRIS") {
										window.location.href = "../app/#/dashboard/";
									}
									else if(humano_module === "KRONO") {
										window.location.href = "../krono/#/dashboard/";
									}
									else if(humano_module === "PAYROLL") {
										window.location.href = "../payroll/#/dashboard/";
									}else {
                                        window.location.href = "/auth/#/login/";
                                    }								
								}  
								else if (data.Type === "Employee") {
									if(humano_module === "HRIS") {
										window.location.href = "../app/#/profile/" + data.EmployeeId;
                                      	//localStorage.clear();
										//alert("This page is underconstruction!");
                                      	//window.location.reload();
                                      	//break;
									}
									else if(humano_module === "KRONO") {
										//window.location.href = "../krono/#/profile/" + data.EmployeeId;
										window.location.href = "../krono/#/employee/dashboard/" + data.EmployeeId;
									}
									else if(humano_module === "PAYROLL") {
										//window.location.href = "../payroll/#/profile/" + data.EmployeeId;
										localStorage.clear();
										alert("Only authorized persons are allowed!");
										break;
									}else{
                                        window.location.href = "/auth/#/login/";
                                    }
								} 
								else {
									window.location.href = "/auth/#/login/";
								}
								break;
							case 2:
								localStorage.clear();
								alert("Invalid User name or Password");
                            	window.location.reload();
                                break;
                            case 3:
                                localStorage.clear();
                                alert("Please check recaptcha");
                                window.location.reload();
                                break;
							case 0:
								localStorage.clear();
								alert("Account Doesn't Exist!");
                            	window.location.reload();
								break;
						}
                    }
                });
                }else{
                 localStorage.clear();
                 alert("Please check recaptcha");
                 window.location.reload();
                }
            });
        });
        //}).enter(clearPanel);

        Path.map("#/create/").to(function() {
            App.canvas.html("").append($.Mustache.render('create'));

            $("#newEmployeeForm").submit(function(e){
                e.preventDefault();

                var firstname = $("input[name=firstname]").val();
                var middlename = $("input[name=middlename]").val();
                var lastname = $("input[name=lastname]").val();
                var marital = $("select[name=marital]").val();
                var usertype = $("select[name=usertype]").val();
                var username = $("input[name=username]").val();
                var password = $("input[name=password]").val();

                if(!firstname || !middlename || !lastname || !marital || !usertype || !username || !password){
                    alert("Please Fill All The Fields!");
                }else{
                    $.ajax({
                        type: "POST",
                        url: App.api + "/employee/new/",
                        dataType: "json",
                        data: {
                            firstname : firstname,
                            middlename : middlename,
                            lastname : lastname,
                            marital : marital,
                            usertype : usertype,
                            username : username,
                            password : password
                        },
                        success: function(data){
                            if(data.error == 1){
                                alert(data.errorMessage);
                            }else{
                                alert(data.errorMessage);
                                window.location.hash = "#/login/";
                            }
                            // alert("SUCCESSFULLY ADDED!");
                        }
                    });
                }//end of comparison

            });
        });

        Path.root("/login/");
        Path.listen();
    });
});