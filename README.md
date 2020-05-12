# Music With A Master

Our mission is to support professional musicians who are unable to perform publicly in these difficult times by connecting them with passionate students desiring to learn and share their love of music. Our program is meant to be in addition to supporting your local teachers.

[View Site](https://musicwithamaster.com/)
[Sign Up](https://docs.google.com/forms/d/e/1FAIpQLSeG-wr2tON3pve3qzVxsTNbrPMY160Fbavj7O2_4N1xgUPW7w/viewform?usp=sf_link)

## Docs

This is a `WordPress` site due to the simple nature of adding musicians as if they were blog posts - enabling built in search and contact functionality while ensuring a smooth user experience.

It uses `SMTP` and `Gmail` to enable site visitors to send messages to their desired teacher without gaining access to the teacher's private email address while still allowing the teachers to easily respond to their students.

The site is hosted on a `Vultr VPS` and routed through `Cloudflare`

Run `systemctl enable --now maldet` or `systemctl disable --now maldet` to control Maldet

You can use Cockpit, a control panel, to manage your instance and software.

````https://104.156.255.167:9080
User: root
Pass: (server root password)
```

If you lose root password:

```
Using bash as init
Append the init=/bin/bash kernel parameter to your boot loader's boot entry.
Your root file system is mounted as read-only now, so remount it as read/write: mount -n -o remount,rw /.
Use the passwd command to create a new password for the root user.
Reboot by typing reboot -f and do not lose your password again!```
````
