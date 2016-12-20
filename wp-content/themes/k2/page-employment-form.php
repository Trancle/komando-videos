<!doctype html>
<?php
    /**
     * Author: gilbert
     * Date: 7/28/2016
     * Time: 4:42 PM
     */
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
<style>
    body {
        background-color: #e8f2ff;
    }

</style>
</head>
<body>
<form id="employment-application">
    <div class="emp-form">
        <tr>
            <td class="emp-top" colspan="4">
                <span class="emp-form-title">WestStar Multimedia Entertainment, Inc.<br>Employment Application</span>
            </td>
        </tr>
        <tr>
            <td class="emp-form-header" colspan="4">
                <p class="emp-policy"><i>It is our policy to provide equal employment opportunities and will not
                    unlawfully consider any factors of race, religion, age, creed, national origin, genter disability,
                    sexual orientation, veteran status, genetic information, or any and all other unlawful biases
                    regarding federal, state, or local laws with regard to workers or applicants.</i>
                </p>
                <p class="emp-form-instructions">TO BE CONSIDERED FOR EMPLOYMENT, ALL APPLICANTS MUST FILL OUT THIS
                    FORM COMPLETELY. THIS APPLICATION WILL BE GIVEN EVERY CONSIDERATION, BUT ITS RECEIPT DOES NOT IMPLY
                    THAT THE APPLICANT WILL BE EMPLOYED BY OUR COMPANY. THIS FORM BECOMES PART OF YOUR EMPLOYMENT
                    RECORD IF YOU ARE HIRED. THIS APPLICATION IS ONLY VALID FOR 30 DAYS.
                </p>
            </td>
        </tr>
        <tr>
            <td class="emp-section-title" colspan="4">Personal Information</td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one, top-row-one" colspan="1">
                First Name: <input class="textbox" tabindex="" type="text" value="" size="20" required name="first_name" />
            </td>
            <td class="emp-form-input, col-a-four, top-row-three" colspan="2">
                Last Name: <input class="textbox" tabindex="" type="text" value="" size="20" required name="last_name" />
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-four" colspan="3">
                Are you 18 years or older:
                <input class="radiobutton" tabindex="" type="radio" value="yes" name="appl-age" />Yes
                <input class="radiobutton" tabindex="" type="radio" value="no" name="appl-age" />No
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="1">
                Telephone: <input class="textbox" tabindex="" type="tel" value="" size="20" required name="appl_telephone" />
            </td>
            <td class="emp-form-input" colspan="1">
                Alt/Cell Phone: <input class="textbox" tabindex="" type="tel" value="" size="20" name="cell_phone" />
            </td>
            <td class="emp-form-input, col-a-four" colspan="2">
                Email: <input class="textbox" tabindex="" type="email" value="" size="25" maxlength="75" required name="appl_email" />
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="1">
                Present Address: <input class="textbox" tabindex="" type="text" value="" size="22" maxlength="50" required name="present_address" />
            </td>
            <td class="emp-form-input, col-a-two" colspan="1">
                City: <input class="textbox" tabindex="" type="text" value="" size="20" required name="appl_city" />
            </td>
            <td class="emp-form-input, col-a-three" colspan="1">
                State: <input class="textbox" tabindex="" type="text" value="" size="2" required name="appl_state" />
            </td>
            <td class="emp-form-input, col-a-four" colspan="1">
                ZIP: <input class="textbox" tabindex="" type="text" value="" size="10" required name="appl_zip" />
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="4">
                <span class="emp-form-directions">If you have lived at the above address for less than 12 months,
                please list prior address:</span>
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="1">
                Prior Address: <input class="textbox" tabindex="" type="text" value="" size="22" maxlength="50" name="prev_address" />
            </td>
            <td class="emp-form-input" colspan="1">
                City: <input class="textbox" tabindex="" type="text" value="" size="20" name="prev_city" />
            </td>
            <td class="emp-form-input" colspan="1">
                State: <input class="textbox" tabindex="" type="text" value="" size="2" name="prev_state" />
            </td>
            <td class="emp-form-input, col-a-four" colspan="1">
                ZIP: <input class="textbox" tabindex="" type="text" value="" size="10" name="prev_zip" />
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="4">
                Have you worked or do you have work experience or education under a different name:
                <input class="radiobutton" tabindex="" type="radio" value="yes" name="different-name" />Yes
                <input class="radiobutton" tabindex="" type="radio" value="no" name="different-name" />No
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="2">
                If yes, please list names (including first, middle, and last):
                <input class="textbox" tabindex="" type="text" value="" size="50" name="alias_name" />
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="4">
                Can you supply documentation of your identity and authorization to work in the U.S.?
                <input class="radiobutton" tabindex="" type="radio" value="yes" name="identity-usa" />Yes
                <input class="radiobutton" tabindex="" type="radio" value="no" name="identity-usa" />No
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="4">
                Have you ever been convicted or plead guilty or no contest to any criminal offense?
                <input class="radiobutton" tabindex="" type="radio" value="yes" name="criminal" />Yes
                <input class="radiobutton" tabindex="" type="radio" value="no" name="criminal" />No<br>
                <span class="explain"><i>(Criminal convictions are not an automatic ban from employment, but only will be considered
                in relation to specific job requirements.)</i></span>
            </td>
        </tr>
        <tr>
            <td class="emp-form-input, col-a-one" colspan="4">
                If yes, state the offense, location, date, and disposition and any other circumstances or rehabilitation:<br>
                <textarea class="textarea-one" tabindex="" maxlength="2000" name="explain-offense"></textarea>
            </td>
        </tr>
    </div>

    <table class="emp-table">
        <tr>
            <td class="emp-section-title, col-b-one" colspan="4">Work Interest</td>
            <td class="col-b-two">&nbsp;</td><td class="col-b-three">&nbsp;</td>
            <td class="col-b-four">&nbsp;</td><td class="col-b-five"></td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="3">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="3">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="1">
            </td>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="2">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="3">
            </td>
            <td class="emp-form-input" colspan="1">
            </td>
            <td class="emp-form-input" colspan="1">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="3">
            </td>
            <td class="emp-form-input" colspan="1">
            </td>
            <td class="emp-form-input" colspan="1">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="5">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="3">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="5">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="5">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="5">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="5">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="3">
            </td>
        </tr>
        <tr>
            <td class="emp-form-input" colspan="2">
            </td>
            <td class="emp-form-input" colspan="3">
            </td>
        </tr>







</form>
</body>

</html>
