You can reset a Windows password using several methods:

## Safe Mode with Command Prompt

1. Boot to Windows Recovery Environment (hold Shift while clicking Restart)
2. Go to Troubleshoot > Advanced Options > Command Prompt
3. Enable the built-in Administrator account:
   ```
   net user administrator /active:yes
   ```
4. Restart and log in as Administrator
5. Reset your password:
   ```
   net user [username] [newpassword]
   ```

## Alternative Methods

**Password Reset Disk**: If you created one previously, use it at the login screen.

**Microsoft Account**: If your account is linked to Microsoft, reset online at account.microsoft.com.

**Another Admin Account**: If another admin exists, they can reset your password through User Accounts in Control Panel.

**Third-party Tools**: Tools like Ophcrack or Kon-Boot can bypass/reset passwords from bootable media.

## Security Note

The built-in Administrator account bypass works because it's often enabled by default in recovery environments. After regaining access, disable it for security:

```
net user administrator /active:no
```

---

In Safe Mode (Mode sans échec):

1. **Access Safe Mode**:

   - Hold Shift + click Restart
   - Or boot from recovery media
   - Choose Troubleshoot > Advanced Options > Startup Settings > Restart
   - Press F4 for Safe Mode with Command Prompt

2. **Reset Password**:

   ```
   net user [username] [newpassword]
   ```

3. **If that fails, enable Administrator**:

   ```
   net user administrator /active:yes
   ```

   Then restart, login as Administrator, and reset your user password.

4. **Alternative using lusrmgr.msc**:
   ```
   lusrmgr.msc
   ```
   Right-click your user → Set Password

## Safe Mode bypasses most security restrictions, making password resets more reliable than normal Windows startup.

Perfect! You're in the Windows recovery environment. The X:\ drive is the temporary recovery system.

**First, switch to the actual Windows drive:**

```
C:
cd \
```

**Now run these commands:**

1. **Disable fast startup:**

```
powercfg /hibernate off
```

2. **Enable Administrator account:**

```
net user administrator /active:yes
```

3. **List users to see available accounts:**

```
net user
```

4. **Reset password for your user:**

```
net user [your-username] [new-password]
```

5. **Exit and restart:**

```
exit
```

Remove the USB and boot normally. You can now login as Administrator or with your reset password.
