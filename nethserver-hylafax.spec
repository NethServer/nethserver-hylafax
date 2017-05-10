Summary: NethServer module to configure Hylafax+
Name: nethserver-hylafax
Version: 1.2.3
Release: 1%{?dist}
License: GPL
Group: Networking/Daemons
Source: %{name}-%{version}.tar.gz
Packager: Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
BuildArch: noarch
Requires: hylafax >= hylafax-5.5.0, urw-fonts, perl-Mail-Sendmail, enscript
Requires: nethserver-mail-server
BuildRequires: nethserver-devtools

%description
NethServer module to configure Hylafax+


%prep
%setup

%pre

%post

%preun

%postun

%build
%{makedocs}
perl createlinks
cd root;
mkdir -p var/spool/hylafax/notify
mkdir -p var/lib/nethserver/fax/docs/sent
mkdir -p var/lib/nethserver/fax/docs/received
mkdir -p var/lock/lockdev

%install
rm -rf %{buildroot}
(cd root; find . -depth -print | cpio -dump %{buildroot})

%{genfilelist} \
    --dir /var/lib/nethserver/fax/docs/ 'attr(0775,uucp,uucp)' \
    --dir /var/lib/nethserver/fax/docs/received 'attr(0775,uucp,uucp)' \
    --dir /var/lib/nethserver/fax/docs/sent 'attr(0775,uucp,uucp)' \
    --dir /var/lock/lockdev 'attr(0775,uucp,uucp)' \
    --file /usr/sbin/neth-addmodem 'attr(0755,root,root)' \
    --file /usr/sbin/neth-faxsetup 'attr(0755,root,root)' \
    --file /var/spool/hylafax/bin/mail2fax.sh 'attr(0755,root,root)' \
    --file /var/spool/hylafax/bin/notify.report 'attr(0755,root,root)' \
    --file /var/spool/hylafax/etc/FaxAccounting 'attr(0755,root,root)' \
    --file /var/spool/hylafax/etc/accounting/00savefiles 'attr(0755,root,root)' \
    --file /var/spool/hylafax/etc/dispatch/90print 'attr(0755,root,root)' \
    --file /usr/lib/cups/backend/salsafax 'attr(0755,root,root)' \
    --ignoredir /var/spool/hylafax/etc \
    --ignoredir /var/spool/hylafax \
    --file /var/spool/hylafax/etc/setup.cache 'attr(0644,uucp,uucp)' \
    %{buildroot} > %{name}-%{version}-%{release}-filelist


%files -f %{name}-%{version}-%{release}-filelist
%defattr(0644,root,root)
%dir %{_nseventsdir}/%{name}-update
%doc COPYING
%doc README.rst

%changelog
* Wed May 10 2017 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.2.3-1
- Upgrade from NS 6 via backup and restore - NethServer/dev#5234

* Mon Mar 06 2017 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.2.2-1
- Migration from sme8 - NethServer/dev#5196

* Mon Oct 17 2016 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.2.1-1
- Unable to make FTP connection with virtualhost - Bug NethServer/dev#5127

* Thu Jul 07 2016 Stefano Fancello <stefano.fancello@nethesis.it> - 1.2.0-1
- First NS7 release

* Fri May 20 2016 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.6-1
- mail2fax transport expansion error - Bug #3391 [NethServer]
- Improve fax report quality - Enhancement #3378 [NethServer]

* Wed Dec 09 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.5-1
- Mail to fax - Feature #1612 [NethServer]

* Tue Sep 29 2015 Davide Principi <davide.principi@nethesis.it> - 1.1.4-1
- Make Italian language pack optional - Enhancement #3265 [NethServer]

* Thu Sep 24 2015 Davide Principi <davide.principi@nethesis.it> - 1.1.3-1
- Drop lokkit support, always use shorewall - Enhancement #3258 [NethServer]

* Wed Jul 15 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.2-1
- Event trusted-networks-modify - Enhancement #3195 [NethServer]

* Fri Apr 10 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.1-1
- Fax: cannot set up a physical modem - Bug #3113 [NethServer]

* Thu Mar 05 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.0-1
- IAXmodem: configure email address for each modem - Feature #2760 [NethServer]

* Thu Oct 30 2014 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.10-1.ns6
- Enable passive mode fax submission - Enhancement #2937 [NethServer]

* Wed Oct 15 2014 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.9-1.ns6
- Backup: move seqf to data backup - Feature #2739

* Thu Oct 02 2014 Davide Principi <davide.principi@nethesis.it> - 1.0.8-1.ns6
- Hylafax: after migration faxes can't be sent or received - Bug #2790 [NethServer]

* Fri Jun 06 2014 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.7-1.ns6
- Fix notify if tsi has spaces - Bug #2746
- Fix automatic fax printing - Bug #2742
- Missing Italian translation - Bug #2706

* Thu Apr 17 2014 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.6-1.ns6
- Probe for physical modem is not probed - Bug #2720

* Wed Feb 26 2014 Davide Principi <davide.principi@nethesis.it> - 1.0.5-1.ns6
- Revamp web UI style - Enhancement #2656 [NethServer]

* Wed Feb 05 2014 Davide Principi <davide.principi@nethesis.it> - 1.0.4-1.ns6
- NethCamp 2014 - Task #2618 [NethServer]
- HylaFAX usage reports not sent - Bug #2586 [NethServer]
- Move admin user in LDAP DB - Feature #2492 [NethServer]
- Update all inline help documentation - Task #1780 [NethServer]

* Thu Oct 17 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.3-1.ns6
- Fix clients connection to hfaxd #2209
- Db defaults: remove Runlevels property #2067 

* Tue Apr 30 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.2-1.ns6
- Rebuild for automatic package handling. #1870
- Add migration code #1699
- Various fixes: #1860 #1833 #1827

* Tue Mar 26 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.1-1.ns6
- Support virtual modem #1617
- Refactor mail notification UI #1782

* Tue Mar 19 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.0-1.ns6
- First release

