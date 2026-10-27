# StudentHub — Practical 1: Project Initiation and Planning

## Aim
To initiate the StudentHub college portal project by identifying its purpose, users, requirements, pages, navigation, basic wireframes, and version-control plan.

## 1. Problem statement
Students often need to check campus events, notices, and student services in different places. StudentHub is a simple college portal that brings these services together in one website. Students can view events, manage their profile, send feedback, and find contact information. Administrators can manage events and review registrations/feedback in later project stages.

## 2. Objectives
- Create a clear and easy-to-use college portal.
- Provide separate student and administrator workflows.
- Make important pages reachable through consistent navigation.
- Build the project incrementally across Practicals 2–14.
- Keep the project files organized and track changes with Git.

## 3. Users and roles
| Role | Main activities |
|---|---|
| Student/visitor | View Home, About, Events, FAQ, and Contact; register or log in |
| Logged-in student | Open Dashboard and Profile; view events; send feedback |
| Administrator | Open Admin area; manage events and review registrations/feedback in later practicals |

## 4. Functional requirements
1. The website shall provide Home and About pages.
2. The website shall provide registration and login forms.
3. A dashboard and profile page shall be available for the student workflow.
4. An Events page shall show campus event information.
5. FAQ shall answer common questions.
6. Feedback shall provide a form for comments.
7. Contact shall display college contact information/a contact form.
8. An Admin page shall represent administrator functions.
9. Navigation links shall connect the pages.
10. Later practicals may add validation, database access, authentication, and CRUD operations.

## 5. Non-functional requirements
- Use valid semantic HTML5.
- Use clear labels and readable text.
- Use meaningful page titles and navigation labels.
- Keep file names simple and consistent.
- Add CSS and JavaScript in later practicals without mixing unrelated concerns.
- Validate user input and protect authenticated/admin functions in the later PHP practicals.

## 6. Sitemap
```text
StudentHub
├── Home (index.html)
├── About (about.html)
├── Register (register.html)
├── Login (login.html)
├── Dashboard (dashboard.html)
│   ├── Profile (profile.html)
│   └── Events (events.html)
├── FAQ (faq.html)
├── Feedback (feedback.html)
├── Contact (contact.html)
└── Admin (admin.html)
```

## 7. Low-fidelity wireframes

### Home page
```text
+--------------------------------------------------------------+
| StudentHub             Home About Events Register Login      |
+--------------------------------------------------------------+
| Welcome to StudentHub                                        |
| Explore campus events, notices and student services.         |
| [View Events]                         [Create Account]        |
+--------------------------------------------------------------+
| Upcoming Events           | Student Services                 |
| - Tech Fest               | - Dashboard                      |
| - Sports Day              | - FAQ / Contact                  |
+--------------------------------------------------------------+
|                         Footer                               |
+--------------------------------------------------------------+
```

### Login / registration page
```text
+--------------------------------------------------------------+
| StudentHub navigation                                        |
+--------------------------------------------------------------+
|                    Sign in / Create account                   |
| Email:       [____________________________________]          |
| Password:    [____________________________________]          |
|              [Submit]                                        |
| New user? Register / Existing user? Login                    |
+--------------------------------------------------------------+
|                         Footer                               |
+--------------------------------------------------------------+
```

### Dashboard page
```text
+--------------------------------------------------------------+
| StudentHub navigation                         Student name    |
+--------------------------------------------------------------+
| Student Dashboard                                            |
| [Profile] [Browse Events] [Feedback] [Contact]                |
| Recent notices / event summaries                              |
+--------------------------------------------------------------+
|                         Footer                               |
+--------------------------------------------------------------+
```

## 8. Proposed folder structure
```text
StudentHub_Practicals/
├── Practical_1_Project_Initiation/
│   ├── README.md
│   ├── requirements.md
│   ├── sitemap.md
│   └── wireframes.md
└── Practical_2_Semantic_HTML/
    ├── index.html
    ├── about.html
    ├── register.html
    ├── login.html
    ├── dashboard.html
    ├── events.html
    ├── profile.html
    ├── faq.html
    ├── feedback.html
    ├── contact.html
    ├── admin.html
    └── README.md
```

## 9. Git/GitHub setup
Run these commands from the main project directory. Replace the repository URL with your own GitHub repository URL.

```bash
git init
git add .
git commit -m "Practical 1 - StudentHub project initiation"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/studenthub.git
git push -u origin main
```

If a remote is already configured, do not run `git remote add origin` again; check it with `git remote -v`.

## 10. Deliverables checklist
- [x] Problem statement and objectives
- [x] User roles
- [x] Functional and non-functional requirements
- [x] Sitemap
- [x] Low-fidelity wireframes
- [x] Proposed folder structure
- [x] Git/GitHub setup instructions

## Result
The StudentHub project scope, requirements, navigation structure, wireframes, and version-control plan have been defined. Practical 2 implements the initial pages using semantic HTML5.
