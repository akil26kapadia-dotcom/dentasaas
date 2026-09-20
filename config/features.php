<?php

/*
 * Content for /features and /features/{slug}. Every claim here is checked against what the
 * product actually does. Keep it that way: no invented numbers, no features that don't exist.
 */
return [

    'pages' => [

        'appointment-scheduling' => [
            'name' => 'Appointments',
            'icon' => 'fa-calendar-check',
            'summary' => 'Book, confirm and track every visit, with one-click WhatsApp confirmations.',
            'title' => 'Dental Appointment Scheduling Software',
            'description' => 'Book, confirm and track dental appointments in one place. Assign doctors, pick services and send one-click WhatsApp confirmations. Free plan available.',
            'h1' => 'Dental appointment scheduling your front desk will actually use',
            'lead' => "Replace the paper diary and the scattered WhatsApp chats with one schedule. Book a visit in a few taps, see today's patients at a glance, and confirm slots with a ready-made WhatsApp message.",
            'benefits' => [
                ['title' => 'Book in seconds', 'text' => 'Search the patient by name or phone, choose the service and doctor, set the date and time, and save. Add a note for anything the doctor should know before the visit.'],
                ['title' => 'A clear status for every visit', 'text' => 'Each appointment moves from pending to confirmed, then completed or cancelled. Status cards on the appointments page show how many visits sit in each state, so nothing slips through.'],
                ['title' => 'One-click WhatsApp confirmation', 'text' => "When you confirm an appointment, DentaSaaS prepares a WhatsApp message with the patient's name, date, time, service and your clinic name. You review it and tap send."],
                ['title' => 'Doctor-wise scheduling', 'text' => "Assign each appointment to a doctor. The doctor is notified when it is confirmed, and a daily reminder covers tomorrow's confirmed appointments."],
                ['title' => "Today's schedule on the dashboard", 'text' => "The dashboard lists today's appointments with time, patient, service and doctor, so the front desk and the doctors start the day on the same page."],
                ['title' => 'English and Hindi', 'text' => 'Switch the whole interface between English and Hindi for staff who are more comfortable in either language.'],
            ],
            'steps' => [
                ['title' => 'Set up once', 'text' => 'Add your services and your doctors. This takes a few minutes and is reused for every appointment and invoice.'],
                ['title' => 'Book the visit', 'text' => 'Pick the patient, service, doctor, date and time. New patient? Add them on the spot.'],
                ['title' => 'Confirm and message', 'text' => 'Mark the appointment confirmed and send the prepared WhatsApp confirmation.'],
                ['title' => 'Complete and bill', 'text' => 'After the visit, mark it completed and create the invoice from the same patient record.'],
            ],
            'sections' => [
                [
                    'heading' => 'Why a dedicated scheduler beats a shared diary',
                    'body' => [
                        'A paper diary cannot tell you which visits are still unconfirmed, cannot remind a doctor about tomorrow, and cannot connect a booking to the patient\'s history or the bill that follows. A group chat is worse: bookings scroll away and nobody is sure which message is the latest.',
                        'In DentaSaaS every appointment is tied to a patient record, a service and a doctor. When the visit is done, the same record leads straight to the invoice and, if needed, a prescription or the next session of a treatment plan.',
                    ],
                ],
                [
                    'heading' => 'Fewer no-shows, without extra tools',
                    'body' => [
                        'The simplest way to reduce no-shows is to confirm every appointment and remind patients close to the visit. DentaSaaS makes that a one-tap job: the message is written for you, addressed to the right number, and opens in your own WhatsApp.',
                        'To be clear about how it works: DentaSaaS does not send WhatsApp messages by itself. It prepares the message and you press send. That keeps you in control of what your patients receive and avoids paying for a separate messaging service.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => 'Does DentaSaaS send WhatsApp messages automatically?', 'a' => "No. It prepares a confirmation or reminder with the patient's details already filled in and opens WhatsApp so you can review and send it. Nothing is sent without your tap."],
                ['q' => 'Can I schedule for more than one doctor?', 'a' => 'Yes. Add your doctors under Doctors and assign each appointment to one. How many doctors you can add depends on your plan; see the pricing page.'],
                ['q' => 'Is there a free plan?', 'a' => 'Yes. The Free plan is permanent, with no trial that expires and no credit card. Higher plans add more capacity and features.'],
            ],
            'related' => ['patient-records', 'dental-billing-invoicing', 'treatment-plans'],
        ],

        'patient-records' => [
            'name' => 'Patient records',
            'icon' => 'fa-users',
            'summary' => "Complete patient profiles with allergies, notes and full visit history.",
            'title' => 'Dental Patient Records Software',
            'description' => "Keep dental patient records organised: contact details, allergies, blood group and a full history of visits, invoices and prescriptions. Each clinic's data stays private.",
            'h1' => 'Dental patient records that are complete, searchable and private',
            'lead' => "One profile per patient, with the details a dentist needs before the chair: contact information, allergies, blood group, notes, and every appointment, invoice, prescription and treatment plan in one place.",
            'benefits' => [
                ['title' => 'One profile per patient', 'text' => 'Name, phone, email, date of birth, gender, blood group and address, all in a single record that the whole team can rely on.'],
                ['title' => 'Allergies and notes up front', 'text' => 'Record allergies and clinical notes where every doctor sees them, instead of in a margin of a paper card.'],
                ['title' => 'The full history in one place', 'text' => "A patient's page lists their appointments, invoices, prescriptions and treatment plans, so returning patients never start from zero."],
                ['title' => 'Find anyone quickly', 'text' => 'Search by name, phone number or email. Selecting a patient while booking or billing works the same way.'],
                ['title' => 'Private to your clinic', 'text' => "Each clinic's records are kept separate from every other clinic. Staff sign in with their own accounts, and passwords are stored hashed."],
                ['title' => 'Works on any device', 'text' => 'Open records on the front-desk computer, a tablet in the treatment room or your phone. There is nothing to install.'],
            ],
            'steps' => [
                ['title' => 'Add the patient', 'text' => 'Enter the basics once: name and phone are enough to begin.'],
                ['title' => 'Add what matters clinically', 'text' => 'Fill in allergies, blood group and notes as you learn them.'],
                ['title' => 'Everything links to the record', 'text' => 'Appointments, invoices, prescriptions and treatment plans are attached to the patient automatically.'],
            ],
            'sections' => [
                [
                    'heading' => 'What to keep in a dental patient record',
                    'body' => [
                        'A useful record answers the questions you ask at every first visit: who is this person, how do we reach them, what should we be careful about, and what have we already done for them.',
                        'That means contact details and date of birth, blood group, known allergies and relevant medical notes, and then a running history of visits, treatments, prescriptions and payments. DentaSaaS gives each of these a place so they are recorded as a by-product of normal work rather than as extra paperwork.',
                    ],
                ],
                [
                    'heading' => 'Keeping patient data private',
                    'body' => [
                        "Patient information is sensitive. In DentaSaaS every clinic's data is isolated, staff have individual logins, and access can be switched off for a team member who leaves. Our support team may open a clinic's account only to help with a request, and a banner makes that visible while it happens.",
                        'For a plain-language walk-through of what to think about as a clinic owner, read our guide to keeping dental patient records private in India.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => "Who can see my patients' records?", 'a' => "Only the staff accounts you create for your clinic. Other clinics cannot see your data. Our support team can view an account only to assist with a request, and the account shows a banner while that is happening."],
                ['q' => 'Does DentaSaaS store X-rays or scans?', 'a' => "No. DentaSaaS stores patient details, notes and history. It is a practice management tool, not an imaging system."],
                ['q' => 'How many patients can I add?', 'a' => 'The limit depends on your plan. The Free plan has a smaller limit and the paid plans raise it, up to unlimited. See the pricing page for the current numbers.'],
            ],
            'related' => ['appointment-scheduling', 'digital-prescriptions', 'dental-billing-invoicing'],
        ],

        'dental-billing-invoicing' => [
            'name' => 'Billing & invoices',
            'icon' => 'fa-file-invoice',
            'summary' => 'Invoices from your service list, with discount, GST and paid/unpaid tracking.',
            'title' => 'Dental Billing & Invoicing Software (GST-ready)',
            'description' => 'Create dental invoices from your service list in seconds. Add discount and GST, track paid and unpaid bills, and print or download a PDF with your clinic details.',
            'h1' => 'Dental billing and invoicing without the spreadsheet',
            'lead' => 'Build an invoice from your own service list, add a discount and GST if they apply, and hand the patient a clean printout or PDF. Every invoice is numbered automatically and tracked as paid or unpaid.',
            'benefits' => [
                ['title' => 'Built from your service list', 'text' => 'Add your treatments and prices once. Choosing a service fills in the price, and you can still adjust the quantity or amount on any invoice.'],
                ['title' => 'Discount and GST', 'text' => 'Apply a discount percentage and a tax or GST percentage per invoice. The subtotal, discount, GST and grand total are calculated for you.'],
                ['title' => 'Automatic invoice numbers', 'text' => 'Invoices are numbered automatically, so you never have to remember the last one used.'],
                ['title' => 'Paid and unpaid at a glance', 'text' => 'The invoices page totals what has been paid and what is still outstanding, and lets you flip an invoice between the two.'],
                ['title' => 'Print or download a PDF', 'text' => 'On paid plans, print an invoice or download it as a PDF that carries your clinic name, logo and GSTIN. The Free plan does not include PDF export.'],
                ['title' => 'Linked to the patient', 'text' => "Invoices sit on the patient's record, so their billing history is one click away."],
            ],
            'steps' => [
                ['title' => 'Add your services', 'text' => 'Enter each treatment with its price and typical duration.'],
                ['title' => 'Create the invoice', 'text' => 'Choose the patient, add line items from your service list, then set discount and GST if needed.'],
                ['title' => 'Give it to the patient', 'text' => 'Print it or download the PDF, and mark it paid when the patient pays.'],
            ],
            'sections' => [
                [
                    'heading' => 'What a clear dental invoice includes',
                    'body' => [
                        "A patient should be able to read an invoice and understand it: your clinic's name and contact details, the invoice number and date, the patient's name, each treatment with quantity and price, any discount, any tax, and the total.",
                        'DentaSaaS puts these on every invoice. If your clinic is registered for GST, your GSTIN is printed on it as well, and the GST amount is shown as its own line.',
                    ],
                ],
                [
                    'heading' => 'A note on GST',
                    'body' => [
                        'Whether GST applies to your services depends on your registration and on the treatment, so please confirm the right rate with your chartered accountant. DentaSaaS lets you set the percentage that applies on each invoice, or leave it at zero.',
                        'DentaSaaS records and prints your invoices. It does not file GST returns and it does not collect payments from patients: you record how each invoice was paid, whether by cash, UPI or card.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => 'Does DentaSaaS file my GST returns?', 'a' => 'No. It helps you issue clear invoices with GST shown where applicable. Filing returns is done separately, usually with your accountant.'],
                ['q' => 'Can patients pay online through DentaSaaS?', 'a' => 'Not at the moment. You record whether an invoice is paid or unpaid; collecting the payment (cash, UPI, card) stays with your clinic.'],
                ['q' => 'Can I print invoices on the Free plan?', 'a' => 'PDF export and printing are part of the paid plans. On the Free plan you can still create invoices and track them as paid or unpaid.'],
            ],
            'related' => ['appointment-scheduling', 'patient-records', 'clinic-analytics'],
        ],

        'digital-prescriptions' => [
            'name' => 'Prescriptions',
            'icon' => 'fa-prescription-bottle-medical',
            'summary' => 'Structured dental prescriptions you can print or download as a PDF.',
            'title' => 'Digital Dental Prescription Software',
            'description' => 'Write dental prescriptions in seconds: diagnosis, medicines, dose, frequency and duration. Print or download a clean PDF for the patient. Available on paid plans.',
            'h1' => 'Digital dental prescriptions that are quick to write and easy to read',
            'lead' => 'Choose the patient, note the diagnosis, add each medicine with its dose, frequency and duration, and produce a clear printed or PDF prescription with your clinic and doctor details on it.',
            'benefits' => [
                ['title' => 'Structured medicines', 'text' => 'Each medicine has a name, dose, frequency, duration and optional instructions, so nothing is left ambiguous.'],
                ['title' => 'Standard frequencies', 'text' => 'Pick from OD, BD, TDS, QID or SOS instead of typing free text every time.'],
                ['title' => 'Several medicines per prescription', 'text' => 'Add as many medicines as the patient needs to a single prescription.'],
                ['title' => 'Print or download', 'text' => 'Produce a clean PDF that carries your clinic details and the prescribing doctor\'s name.'],
                ['title' => "Part of the patient's history", 'text' => "Prescriptions are stored against the patient, so a returning patient's earlier prescriptions are easy to find."],
            ],
            'steps' => [
                ['title' => 'Pick the patient', 'text' => 'Search by name or phone and add an optional diagnosis.'],
                ['title' => 'Add the medicines', 'text' => 'Name, dose, frequency, duration and any instructions for each.'],
                ['title' => 'Print or download', 'text' => 'Hand the patient a printout or send the PDF.'],
            ],
            'sections' => [
                [
                    'heading' => 'What the common frequency codes mean',
                    'body' => [
                        'Dental and medical prescriptions use short codes for how often a medicine is taken. DentaSaaS uses the ones patients and pharmacists in India already know:',
                        "- **OD**: once a day\n- **BD**: twice a day\n- **TDS**: three times a day\n- **QID**: four times a day\n- **SOS**: only when needed",
                    ],
                ],
                [
                    'heading' => 'A tool for documentation, not clinical advice',
                    'body' => [
                        'DentaSaaS helps you write and store prescriptions neatly. It does not suggest medicines or doses, and the prescribing doctor remains responsible for every clinical decision.',
                        'Prescriptions are available on the paid plans; the Free plan focuses on appointments, patients and invoices.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => 'Does DentaSaaS suggest medicines or doses?', 'a' => 'No. You enter every medicine and dose yourself. DentaSaaS only structures and prints what the doctor decides.'],
                ['q' => 'Is prescription writing on the Free plan?', 'a' => 'No, prescriptions are part of the paid plans. See the pricing page for what each plan includes.'],
                ['q' => 'Can the patient get a PDF?', 'a' => 'Yes. You can print the prescription or download it as a PDF to share with the patient.'],
            ],
            'related' => ['patient-records', 'treatment-plans', 'appointment-scheduling'],
        ],

        'treatment-plans' => [
            'name' => 'Treatment plans',
            'icon' => 'fa-diagram-project',
            'summary' => 'Plan multi-visit treatments and track each session on a board.',
            'title' => 'Dental Treatment Plan Software',
            'description' => 'Plan multi-visit dental treatments such as root canals and braces. Track each session on a kanban board, schedule visits and send WhatsApp session reminders.',
            'h1' => 'Multi-visit dental treatment plans, tracked session by session',
            'lead' => 'Root canals, implants and braces take several visits. Record the plan once, schedule each session, and follow progress on a simple board so patients finish what they started.',
            'benefits' => [
                ['title' => 'One plan per treatment', 'text' => 'Record the patient, the doctor, the treatment, the total number of sessions and any notes.'],
                ['title' => 'Sessions with dates', 'text' => 'Give each session a title, a date and a time, and update its status as work progresses.'],
                ['title' => 'A board you can drag', 'text' => 'See plans as cards in Planned, In progress and Completed columns, and drag a card to move it along. Prefer a table? Switch to the list view.'],
                ['title' => 'One-click session reminders', 'text' => 'Prepare a WhatsApp reminder for an upcoming session with the patient, treatment, session and date already filled in.'],
                ['title' => 'Track what has been paid', 'text' => 'Mark individual sessions as paid so you can see at a glance what is still outstanding on a long treatment.'],
                ['title' => 'On the patient record', 'text' => "Treatment plans appear on the patient's profile with the rest of their history."],
            ],
            'steps' => [
                ['title' => 'Create the plan', 'text' => 'Choose the patient and treatment and set how many sessions you expect.'],
                ['title' => 'Schedule the sessions', 'text' => 'Add dates and times as they are agreed with the patient.'],
                ['title' => 'Move it along', 'text' => 'Update each session and drag the plan across the board as treatment progresses.'],
            ],
            'sections' => [
                [
                    'heading' => 'Why multi-visit treatments need their own tracking',
                    'body' => [
                        'A single appointment is easy to remember. A treatment that runs over several weeks is not: patients postpone, forget the next step, or quietly drop out before the treatment is finished.',
                        'A treatment plan gives every long treatment a visible status. You can see which patients are mid-way, which sessions are coming up and which plans have not moved for a while.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => 'Are reminders sent automatically?', 'a' => 'No. DentaSaaS prepares the WhatsApp reminder and opens it so you can send it yourself.'],
                ['q' => 'Can I see all plans at once?', 'a' => 'Yes. The board shows every plan by status, and the list view shows the same plans as a table.'],
                ['q' => 'Do treatment plans work with billing?', 'a' => 'Sessions can be marked as paid, and invoices are created from the same patient record, so treatment and billing stay connected.'],
            ],
            'related' => ['appointment-scheduling', 'patient-records', 'dental-billing-invoicing'],
        ],

        'clinic-analytics' => [
            'name' => 'Analytics',
            'icon' => 'fa-chart-line',
            'summary' => 'Monthly revenue, appointment trends and top services in simple charts.',
            'title' => 'Dental Clinic Analytics and Revenue Reports',
            'description' => 'See monthly revenue, new patients, appointment trends and top services for your dental clinic in simple charts. Included with the Starter and Pro plans.',
            'h1' => 'Know how your dental clinic is doing, without a spreadsheet',
            'lead' => 'Revenue, new patients, appointments and your most-booked services, drawn from the work your team already records. Choose this month, the last three months or this year.',
            'benefits' => [
                ['title' => 'Revenue you can trust', 'text' => 'Revenue is calculated from invoices marked as paid, so the number reflects money actually collected.'],
                ['title' => 'Headline numbers', 'text' => 'Total revenue, new patients, number of appointments and average invoice value for the period you pick.'],
                ['title' => 'Twelve months of revenue', 'text' => 'A month-by-month chart makes busy and slow periods obvious.'],
                ['title' => 'Appointment trend', 'text' => 'See how bookings have moved over the last thirty days and how they are split by status.'],
                ['title' => 'Top services', 'text' => 'Find out which treatments your clinic performs most so you can plan time and stock.'],
            ],
            'steps' => [
                ['title' => 'Record your work', 'text' => 'Book appointments and create invoices as you normally do.'],
                ['title' => 'Mark invoices paid', 'text' => 'Revenue counts invoices once they are marked as paid.'],
                ['title' => 'Open analytics', 'text' => 'Pick a period and read the charts. There is nothing to configure.'],
            ],
            'sections' => [
                [
                    'heading' => 'Numbers that come from daily work',
                    'body' => [
                        'Most small clinics do not have time to build reports. Because DentaSaaS records appointments, patients and invoices as part of daily work, the analytics are a by-product: there is no extra data entry.',
                        'Analytics are included with the Starter plan (basic) and the Pro plan (full). The Free plan does not include them.',
                    ],
                ],
            ],
            'faqs' => [
                ['q' => 'Is revenue counted when an invoice is created?', 'a' => 'Revenue is counted from invoices marked as paid, so unpaid invoices do not inflate the figure.'],
                ['q' => 'Which plans include analytics?', 'a' => 'Starter includes basic analytics and Pro includes full analytics. The Free plan does not include them.'],
                ['q' => 'Can I export the reports?', 'a' => 'The analytics page is designed for viewing on screen. Ask us on WhatsApp if you need something specific and we will consider it.'],
            ],
            'related' => ['dental-billing-invoicing', 'appointment-scheduling', 'patient-records'],
        ],

    ],

    // Smaller capabilities listed on the /features overview.
    'more' => [
        ['icon' => 'fa-user-doctor', 'title' => 'Multi-doctor teams', 'text' => 'Add doctors and staff with their own logins and assign appointments to each doctor.'],
        ['icon' => 'fa-language', 'title' => 'English and Hindi', 'text' => 'Switch the whole interface between English and Hindi at any time.'],
        ['icon' => 'fa-tooth', 'title' => 'Service catalogue', 'text' => 'Keep every treatment, price and duration in one list that feeds appointments and invoices.'],
        ['icon' => 'fa-palette', 'title' => 'Your clinic, your branding', 'text' => 'Add your logo, tagline, address and GSTIN, and choose your theme colour.'],
        ['icon' => 'fa-bell', 'title' => 'Notifications', 'text' => 'In-app notifications for confirmed appointments and plan expiry so nothing is missed.'],
        ['icon' => 'fa-mobile-screen', 'title' => 'Works on any device', 'text' => 'Use it on a desktop, tablet or phone. There is nothing to install.'],
    ],

];
