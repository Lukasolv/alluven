package com.alluven.myapplication

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.alluven.myapplication.ui.ClientDashboardActivity
import com.alluven.myapplication.ui.LoginActivity
import com.alluven.myapplication.ui.admin.AdminDashboardActivity
import com.alluven.myapplication.util.SessionManager

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        val session = SessionManager(this)
        if (!session.isLoggedIn) {
            startActivity(Intent(this, LoginActivity::class.java))
        } else {
            if (session.role.equals("admin", true))
                startActivity(Intent(this, AdminDashboardActivity::class.java))
            else
                startActivity(Intent(this, ClientDashboardActivity::class.java))
        }
        finish()
    }
}
