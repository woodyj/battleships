# Battleships

## Installation
Follow these steps to run battleships app in your local environment. These instructions assume you're using a Linux derived OS.  Instructions for Windows are not currently included.

### System Requirements (Host)
You must install these dependencies before the VM can start:
  - [Vagrant](https://www.vagrantup.com/downloads.html)
  - [VirtualBox](https://www.virtualbox.org/wiki/Downloads)

### Configure Homestead
You will need to update some configuration options before you can start the Vagrant VM.
  - Open the Homestead.yaml file.
  - If necessary change `ip: 192.168.10.10` to a vacant local IP address.
  - Change `home/jim/Code/battleships` to your own local project root.

### Update local hosts file
Add the following entry into your local hosts file `/etc/hosts`:
  - `192.168.10.10 battleships.app` (If you changed the IP in the previous steps, remember to use that IP here instead)

### Start Vagrant VM
  - In the root directory of this project, run `vagrant up`

### Load the project
In your browser, visit: `http://battleships.app`

## Groveling, sniveling excuses
### What I should have done
  - TDD!
  - Focused on quality, rather than trying to be clever with myriad design patterns in a vain bid to impress Mr Ward!
  - Thought more about the effort required to implement my architectural decisions vs sacrificing best practice.
  - Not hacked the frontend code like I have (FFS, don't even look at it PLEASE).
  
### Things to improve on
  - Introduce Iterators to process various Collections (marked with @todo annotation throughout).
  - Strict PHP7 standards - yes, there are violations at the present time.  A very small number of method arguments are not type hinted, and a very small number of methods have no return type hinting. Sometimes because I wanted the flexibility of having multiple types per argument or return, although I'd perfer to go back and reverse that decision.
  - Unit tests, functional tests, maybe even some browser testing...
  - Demonstrate use of Traits and Interfaces, although I so far haven't felt the need to use them in this project.
  - GameFacade???  Not sure Facade is the right convention here!
  - Maybe use an event stream for posting hit messages - a bit fanciful for this short test though, perhaps.
  - See other @todo annotations.