#!/usr/bin/perl -w 
#
# $Id: faxsrv.pm,v0.5  04 Octobre 2004 Christian DUMONT (Gla) Exp $
#

package esmith::FormMagick::Panel::faxsrv;

use strict;
use esmith::ConfigDB;
use esmith::AccountsDB;
use esmith::FormMagick;
use esmith::cgi;
use esmith::util;
use File::Basename;
use Exporter;
use Carp;


our @ISA = qw(esmith::FormMagick Exporter);

our @EXPORT = qw( 
                show_conf
                change_modem_settings 
                get_value
		list_printers
    		list_accounts
	       );

our $db = esmith::ConfigDB->open
          or die("Unable to open the configuration database : $!\n");

sub new {
    shift;
    my $self = esmith::FormMagick->new();
    $self->{calling_package} = (caller)[0];
    bless $self;
    return $self;
}

sub get_value {
  my $fm = shift;
  my $item = shift;
  return ($db->get('hylafax')->prop($item));   
}


sub show_conf {
    my $self = shift ;
    my $q = $self->{cgi};
    my $empty = 0 ;
                                          
# Test de pr?nce du champ Hylafax dans la base de configuration    
    my $mabase = $db->get('hylafax'); 
# *************************************************************
#    my $mabase = $db->get('hylafax')
#        or ($self->error('ERR_NO_FAXSRV_RECORD') and return undef) ;
    # Lecture des donn? de configuration
    my $FaxNumber           = $mabase->prop('FaxNumber');
    my $FaxName             = $mabase->prop('FaxName');
    my $CountryCode         = $mabase->prop('CountryCode');
    my $AreaNumber          = $mabase->prop('AreaNumber');
    my $PBXPrefix           = $mabase->prop('PBXPrefix');
    my $FaxDevice           = $mabase->prop('FaxDevice');
    my $Mode                = $mabase->prop('Mode');
    my $WaitDialTone        = $mabase->prop('WaitDialTone');
    my $NotifyFileType      = $mabase->prop('NotifyFileType');
    my $SendTo		    = $mabase->prop('SendTo');
    my $DispatchFileType    = $mabase->prop('DispatchFileType');
    my $ClientShowReceived  = $mabase->prop('ClientShowReceived');
    my $PrinterName         = $mabase->prop('PrinterName');
    my $SambaFax            = $mabase->prop('SambaFax');

        # Affichage bouton modification config Modem
        print $q->Tr(
                      $q->td({-colspan => 2 },
                            $q->p("<a class=\"button-like\"" ,
                                  "href=\"faxsrv?page=1&page_stack=&Next=ConfigModem\">" ,
                                  $self->localise('BUTTON_LABEL_CREATE') ,
                                  "</a>"
                                  )
                            )
                    
                    ) ;
        # Affichage message tableau
        print $q->Tr(
            $q->td({-colspan => 2},
                $q->p($self->localise('SHOW_FAX_CONF')))),"\n";

        # Affichage Tableau de config
        print $q->start_table({-class => 'sme-border'}), "\n        ";
        print $q->Tr(
            esmith::cgi::genSmallCell(
                $q,
                $self->localise('LABEL_NAME'),
                "header"
            ), "        ",
            esmith::cgi::genSmallCell(
                $q,
                $self->localise('LABEL_VALUE'),
                "header"
            ), "\n        ",
        );
	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('COUNTRY_CODE'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $CountryCode,"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('AREA_NUMBER'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $AreaNumber,"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('NUM_FAX'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $FaxNumber,"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('LOCAL_ID'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $FaxName,"normal"),"\n        ",
                    );
 	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('PBX_PREFIX'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $PBXPrefix,"normal"),"\n        ",
                    );
		  
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('WAITDIALTONE'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($WaitDialTone),"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('COMPORT'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $FaxDevice,"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('MODE'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($Mode),"normal"),"\n        ",
                    );    
	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('NOTIFY_FTYPE'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $NotifyFileType,"normal"),"\n        ",
                    );
	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('SENDTO'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($SendTo),"normal"),"\n        ",
                    );
	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('DISPATCH_FTYPE'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $DispatchFileType,"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('CLIENT_SHOW'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($ClientShowReceived),"normal"),"\n        ",
                    );
	print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('AUTO_PRINT'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($PrinterName),"normal"),"\n        ",
                    );
        print $q->Tr(
                    esmith::cgi::genSmallCell($q, $self->localise('SAMBAFAX'),"normal"),"        ",
                    esmith::cgi::genSmallCell($q, $self->localise($SambaFax),"normal"),"\n        ",
                    );

	print $q->end_table,"\n";
        
     return undef;   
}

sub change_modem_settings {
    my $self = shift ;
    my $q = $self->{'cgi'};
    my %conf;
    tie %conf, 'esmith::config';
 
    my $mabase = $db->get('hylafax')
        or ($self->error('ERR_NO_FAXSRV_RECORD') and return undef) ;

# enregistrement des valeurs dans la base de configuration
    $mabase->set_prop('FaxNumber', $q->param('FaxNumber'));
    $mabase->set_prop('FaxName', $q->param('FaxName'));
    $mabase->set_prop('CountryCode', $q->param('CountryCode'));
    $mabase->set_prop('AreaNumber', $q->param('AreaNumber'));
    $mabase->set_prop('PBXPrefix', $q->param('PBXPrefix'));
    $mabase->set_prop('SendTo', $q->param('SendTo'));
    $mabase->set_prop('SambaFax', $q->param('SambaFax'));
    $mabase->set_prop('FaxDevice', $q->param('FaxDevice'));
    $mabase->set_prop('Mode', $q->param('Mode'));
    $mabase->set_prop('SendTo', $q->param('SendTo'));
    $mabase->set_prop('ClientShowReceived', $q->param('ClientShowReceived'));
    $mabase->set_prop('WaitDialTone', $q->param('WaitDialTone'));
    $mabase->set_prop('PrinterName', $q->param('PrinterName'));
    my $disp = join(',', $q->param('DispatchFileType'));
    $mabase->set_prop('DispatchFileType', $disp);
    my $not = join(',', $q->param('NotifyFileType'));
    $mabase->set_prop('NotifyFileType', $not);
    
    # no modem probe if no FaxDevice configured
    if($q->param('FaxDevice') eq 'none') {
       if (system( "/sbin/e-smith/signal-event hylafax-update" ) == 0) {
          $self->success($self->localise("CONFIG_OK"));
       } else {
          $self->error($self->localise("CONFIG_ERROR"));
       }
    } else {
       # Update config and probe for modem
       if (system( "/etc/e-smith/events/actions/hylafax-update" ) == 0) {
          $self->success($self->localise("CONFIG_OK"));
       } else {
          $self->error($self->localise("CONFIG_ERROR"));
       }
    } 
    $self->wherenext("First");
}

sub parse_printcap {
        my $file = '/etc/printcap';
        my %printers;

        open F,$file or return;
        while (<F>) {
                chomp;
                s/#.*$//;  s/\s+$//;  s/^\s+//;
                next unless $_;
                next if /^:/;
                next if /^\|/;
                next if /^all:/;
                if (/^include (.*)/) {
                        print "c";
                        parse_printcap($_);
                        next;
                }
                s/:.*$//;  s/\|.*$//;
		# exclude sambafax printcap entry
                if($_ ne 'sambafax') { 
			$printers{$_}=$_;
		}
        }
        close F;
        return %printers;
}


sub list_printers {
    my $self = shift;
    my $q = $self->{cgi};
    my %printer_opts = parse_printcap();  
    $printer_opts{'disabled'}='Disabled';
    return \%printer_opts;
}


sub list_accounts {
    my $fm = shift;
    my %existingAccounts = ( 'disabled' => 'Disabled' );
    $existingAccounts{'admin'}="Administrator";
    my $accounts = esmith::AccountsDB->open();;
    foreach my $account ($accounts->get_all) {
        if ($account->prop('type') =~ /(user|group)/) {
            $existingAccounts{$account->key} = $account->key;
        }
        if ($account->prop('type') eq "pseudonym") {
            my $target = $accounts->get($account->prop('Account'));

            unless ($target)
            {
                warn "WARNING: pseudonym ("
                        . $account->key
                        . ") => missing Account("
                        . $account->prop('Account')
                        . ")\n";
                next;
            }

            $existingAccounts{$account->key} = $account->key
                unless ($target->prop('type') eq "pseudonym");
        }
    }
    return(\%existingAccounts);
}


sub get_faxdevice_options
{
    my %options= ( 'ttyS0' => 'COM1 (ttyS0)','ttyS1' => 'COM2 (ttyS1)','ttyS2' => 'COM3 (ttyS2)','ttyACM0' => 'USB (ttyACM0)','iax' => 'IAX Modem', 'none' => 'Nessuno' );

    my $fd = $db->get_prop('hylafax', 'FaxDevice') || 'none';
    if(! exists $options{$fd} )
    {
       $options{$fd} = $fd;
    }

    \%options;
}


1;

