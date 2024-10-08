<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking Confirmation - Elite Car Parking</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        table {
            border-collapse: collapse;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
            display: block;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #333333;
        }

        .header {
            background-color: #2c3e50;
            padding: 20px;
        }

        .header img {
            width: 100px;
            margin: 0 auto;
        }

        .content {
            padding: 20px;
        }

        .important-info {
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
            padding: 15px;
        }

        .booking-details {
            background-color: #f9f9f9;
            padding: 15px;
        }

        .footer {
            background-color: #2c3e50;
            color: #ffffff;
        }

        h1,
        h2 {
            color: #2c3e50;
            margin: 0;
        }

        .header h1 {
            color: #ffffff;
            font-size: 24px;
        }

        .contact {
            color: #3498db;
        }
    </style>
</head>

<body>
<center class="wrapper">
    <table class="main" width="100%">
        <!-- Header -->
        <tr>
            <td class="header" align="center">
                <img src="https://elitecarparking.es/wp-content/uploads/2024/08/ELITE-CAR-PARKING-1-107x107.png"
                     alt="Elite Car Parking Logo" width="100">
                <h1>Elite Car Parking</h1>
                <p style="color: #ffffff;">Avenida del Comandante García Morato, 50, Oficina 4A
                    29004 Málaga, Spain</p>
                <p class="contact">Telephone Number: +34 672 576 394</p>
                <p class="contact">Email Address: eliteparkinganddetailing@gmail.com</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td class="content">
                <!-- Important Information -->
                <table width="100%">
                    <tr>
                        <td class="important-info">
                            <h2>Important Information</h2>
                            <ul>
                                <li>Please review all details in this email carefully.</li>
                                <li>Confirm that all provided information is correct.</li>
                                <li>Any changes or additional information must be emailed to us.</li>
                                <li>Phone calls should be avoided as they do not create a record.</li>
                                <li>There is a 10-minute free parking period enforced by Airport Authorities.</li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <!-- Booking Details -->
                <table width="100%" style="margin-top: 20px;">
                    <tr>
                        <td class="booking-details">
                            <h2>Your Booking Details</h2>
                            <p><strong>Contract Client:</strong> {{ $booking->name }}</p>
                            <p><strong>Contract Client Email:</strong> {{ $booking->email }}</p>
                            <p><strong>Name of Arriving Person:</strong> {{ $booking->visitor_name }}</p>
                            <p><strong>Mobile of Arriving Person:</strong> {{ $booking->visitor_mobile }}</p>
                            <p><strong>Email of Arriving Person:</strong> {{ $booking->visitor_email }}</p>
                            <p><strong>Vehicle Color:</strong> {{ $booking->vehicle_color }}</p>
                            <p><strong>Vehicle Model:</strong> {{ $booking->vehicle_model }} </p>
                            <p><strong>Vehicle Number:</strong> {{ $booking->vehicle_number }} </p>
                            @if ($booking->has_arrival_booking == 'Yes')
                                <p><strong>Has Arrival Booking: </strong> {{ $booking->has_arrival_booking }}</p>
                                <p><strong>Arrival Flight:</strong> {{ $booking->arrival_mode }} on
                                    {{ $booking->arrival_datetime->format('d M Y H:i') }}</p>
                            @endif

                            <p><strong>Hold Luggage:</strong> {{ $booking->has_hold_luggage }}</p>

                            @if ($booking->has_departure_booking == 'Yes')
                                <p><strong>Has Departure Booking: </strong> {{ $booking->has_departure_booking }}
                                <p><strong>Departure Meeting Time:</strong>
                                    {{ $booking->departure_meeting_time->format('d M Y H:i') }} at Departures
                                </p>
                            @endif

                            @if ($booking->additional_info)
                                <p><strong>Additional Information:</strong> {{ $booking->additional_info }}</p>
                            @endif
                        </td>
                    </tr>
                </table>

                <table width="100%" class="terms">
                    <tr>
                        <td>
                            <h2>Terms & Conditions Summary</h2>
                            <p>By accepting this booking, you agree to our full terms and conditions, including:</p>
                            <ul>
                                <li>Vehicles are only accepted with correct documentation.</li>
                                <li>Our responsibility begins upon in-person transfer of the vehicle at departures.
                                </li>
                                <li>A contract/invoice must be signed by both parties to commence responsibility.
                                </li>
                                <li>We are not liable for items left in the vehicle unless registered on the
                                    contract.</li>
                                <li>We are responsible for safe transportation and storage but not for
                                    technical/mechanical issues.</li>
                                <li>Vehicle status checks are performed at collection; please verify upon return.
                                </li>
                                <li>24-hour notice via email is required for vehicle return/collection.</li>
                                <li>Late notice or incorrect information may incur additional charges.</li>
                                <li>Unclaimed vehicles will incur additional fees as per the current price list.
                                </li>
                                <li>Payment in Euros is required for vehicle release.</li>
                            </ul>
                            <p>For full terms and conditions, please visit our website or contact our customer
                                service.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td class="footer" align="center" style="padding: 20px;">
                <p>Thank you for choosing Elite Car Parking.</p>
                <p>If you have any questions, please contact us at <span class="contact">+34 672 576 394</span></p>
            </td>
        </tr>
    </table>
</center>
</body>

</html>
